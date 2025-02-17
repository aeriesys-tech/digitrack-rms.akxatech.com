<?php

namespace App\Imports;

use App\Models\Spare;
use App\Models\SpareAssetType;
use App\Models\AssetType;
use App\Models\SpareAttributeValue;
use App\Models\SpareAttributeType;
use App\Models\SpareAttribute;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;

class SpareImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index =>  $row) 
        {
            $spareTypes = SpareAttributeType::whereHas('SpareType')->with('SpareType')->get()
                ->keyBy(function ($spareAttributeType) {
                    return $spareAttributeType->SpareType->spare_type_name;
                });

            $spareAttributes = SpareAttribute::all()->keyBy('field_key');

            $spareTypeId = $spareTypes->get(trim($row['spare_type'])) ? 
                $spareTypes->get(trim($row['spare_type']))->spare_type_id : null;

            if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type'])) {
                $this->failures[] = new Failure(
                    $index + 1, 
                    'spare_code, spare_name, spare_type',
                    ['Missing required fields']
                );
                continue; 
            }
            
            if (Spare::where('spare_code', trim($row['spare_code']))->exists() || 
                Spare::where('spare_name', trim($row['spare_name']))->exists()) {
                $this->failures[] = new Failure(
                    $index + 1,
                    'spare_code, spare_name',
                    ['Duplicate spare_code or spare_name']
                );
                continue;
            }

            $data = [
                'spare_type_id' => $spareTypeId,
                'spare_code' => trim($row['spare_code']),
                'spare_name' => trim($row['spare_name']),
            ];

            $spare = Spare::create($data);

            // asset types
            if (isset($row['assign_to'])) 
            {
                $assetTypeNames = explode(',', $row['assign_to']);
                $assetTypeDatas = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
            
                if ($assetTypes->isNotEmpty()) 
                {
                    foreach ($assetTypes as $assetType) {
                        $assetTypeId = $assetType->asset_type_id;
            
                        SpareAssetType::create([
                            'spare_id' => $spare->spare_id,
                            'asset_type_id' => $assetTypeId,
                        ]);
                    }
                } 
            }

            //SpareAttribute
            foreach ($row as $key => $value) 
            {
                if (!in_array($key, ['spare_type', 'spare_code', 'spare_name', 'assign_to']) && !empty($value)) 
                {
                    $spareAttribute = $spareAttributes->get($key); 

                    if ($spareAttribute) 
                    {
                        $fieldValue = trim($value);
                        $spareAttributeId = $spareAttribute->spare_attribute_id;

                        if ($spareAttribute->field_type === 'Color') 
                        {
                            $fieldValue = $this->convertColorNameToHex($fieldValue);
                        }
                        if($spareAttribute->field_type === 'Date')
                        {
                            $fieldValue = $this->convertDate($fieldValue);
                        }
                        if($spareAttribute->field_type === 'Date&Time')
                        {
                            $fieldValue = $this->convertDateTime($fieldValue);
                        }
                    
                        SpareAttributeValue::create([
                            'spare_id' => $spare->spare_id,
                            'spare_attribute_id' => $spareAttributeId,
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
