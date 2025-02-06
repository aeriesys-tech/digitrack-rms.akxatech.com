<?php

namespace App\Imports;

use App\Models\Variable;
use App\Models\AssetType;
use App\Models\VariableAssetType;
use App\Models\VariableAttributeValue;
use App\Models\VariableAttributeType;
use App\Models\VariableAttribute;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;

class VariableImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        $variableTypes = VariableAttributeType::whereHas('VariableType')->with('VariableType')->get()
            ->keyBy(function ($variableAttributeType) {
                return $variableAttributeType->VariableType->variable_type_name;
            });

        $variableAttributes = VariableAttribute::all()->keyBy('field_key');

        foreach ($rows as $index => $row) 
        {
            if (!isset($row['variable_code']) || !isset($row['variable_name'])) {
                $this->failures[] = new Failure(
                    $index + 1, 
                    'variable_code, variable_name, spare_type',
                    ['Missing required fields']
                );
                continue; 
            }
            
            if (Variable::where('variable_code', trim($row['variable_code']))->exists() || 
                Variable::where('variable_name', trim($row['variable_name']))->exists()) {
                $this->failures[] = new Failure(
                    $index + 1,
                    'variable_code, variable_name',
                    ['Duplicate variable_code or variable_name']
                );
                continue;
            }

            $variableTypeId = $variableTypes->get(trim($row['variable_type'])) ? 
                $variableTypes->get(trim($row['variable_type']))->variable_type_id : null;

            $data = [
                'variable_type_id' => $variableTypeId,
                'variable_code' => trim($row['variable_code']),
                'variable_name' => trim($row['variable_name']),
            ];

            $variable = Variable::create($data);

            // asset types
            if (isset($row['assign_to'])) 
            {
                $assetTypeNames = array_map('trim', explode(',', $row['assign_to']));
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
                if ($assetTypes->isNotEmpty()) {
                    foreach ($assetTypes as $assetType) {
                        $assetTypeId = $assetType->asset_type_id;
            
                        VariableAssetType::create([
                            'variable_id' => $variable->variable_id,
                            'asset_type_id' => $assetTypeId,
                        ]);
                    }
                } 
            }
            
            foreach ($row as $key => $value) 
            {
                // Skip the known keys like spare_type, spare_code, spare_name, and asset_type
                if (!in_array($key, ['variable_type', 'variable_code', 'variable_name', 'assign_to']) && !empty($value)) 
                {
                    $variableAttribute = $variableAttributes->get($key); 
            
                    if ($variableAttribute) 
                    {
                        $fieldValue = trim($value);

                        $variableAttributeId = $variableAttribute->variable_attribute_id;
                        if ($variableAttribute->field_type === 'Color') 
                        {
                            $fieldValue = $this->convertColorNameToHex($fieldValue);
                        }
                        if($variableAttribute->field_type === 'Date')
                        {
                            $fieldValue = $this->convertDate($fieldValue);
                        }
                        if($variableAttribute->field_type === 'Date&Time')
                        {
                            $fieldValue = $this->convertDateTime($fieldValue);
                        }

                        VariableAttributeValue::create([
                            'variable_id' => $variable->variable_id,
                            'variable_attribute_id' => $variableAttributeId,
                            'field_value' => $fieldValue,
                        ]);
                    }
                }
            }
        }
    }

    public function convertColorNameToHex($fieldValue) 
    {
        $colorNamesToHex = [
            'Red' => '#FF0000',
            'Green' => '#008000',
            'Blue' => '#0000FF',
            'Yellow' => '#FFFF00',
            'Black' => '#000000',
            'White' => '#FFFFFF',
            'Gray' => '#808080',
            'Orange' => '#FFA500',
            'Purple' => '#800080',
            'Pink' => '#FFC0CB',
            'Brown' => '#A52A2A',
            'Cyan' => '#00FFFF',
            'Magenta' => '#FF00FF',
            'Lime' => '#00FF00',
            'Indigo' => '#4B0082',
            'Violet' => '#8A2BE2',
        ];

        if (array_key_exists($fieldValue, $colorNamesToHex)) {
            return $colorNamesToHex[$fieldValue];
        }
        return '#000000';
    }

    public function convertDate($fieldValue) 
    {
        if (is_numeric($fieldValue)) {
            return Date::excelToDateTimeObject($fieldValue)->format('Y-m-d');
        } else if (is_string($fieldValue) && strtotime($fieldValue)) {
            return Carbon::createFromFormat('Y-m-d', $fieldValue)->format('Y-m-d');
        }
        else{
            return '0000-00-00';
        }   
    }

    public function convertDateTime($fieldValue)
    {
        if (is_numeric($fieldValue)) {
            return Date::excelToDateTimeObject($fieldValue)->format('Y-m-d H:i');
        } elseif (is_string($fieldValue) && strtotime($fieldValue)) {
            return Carbon::parse($fieldValue)->format('Y-m-d H:i');
        } else {
            return '0000-00-00 00:00';
        }
    }
}