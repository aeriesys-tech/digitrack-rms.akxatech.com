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

class VariableImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errorRows = [];
  
        $assetTypes = VariableAssetType::whereHas('AssetType')->with('AssetType')->get()
            ->keyBy(function ($variableAssetType) {
                return $variableAssetType->AssetType->asset_type_name;
            });

        $variableTypes = VariableAttributeType::whereHas('VariableType')->with('VariableType')->get()
            ->keyBy(function ($variableAttributeType) {
                return $variableAttributeType->VariableType->variable_type_name;
            });

        $variableAttributes = VariableAttribute::all()->keyBy('field_name');

        foreach ($rows as $row) 
        {
            if (!isset($row['variable_code']) || !isset($row['variable_name']) || !isset($row['variable_type'])) 
            {
                $errorRows[] = $row; 
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
            if (isset($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
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
            // Log::info($row);
            foreach ($row as $key => $value) 
            {
                $normalizedKey = $this->normalizeKey($key);

                // Skip the known keys like spare_type, spare_code, spare_name, and asset_type
                if (!in_array($normalizedKey, ['variable_type', 'variable_code', 'variable_name', 'asset_type']) && !empty($value)) 
                {
                    $variableAttribute = $variableAttributes->get($normalizedKey); 
                    if (!$variableAttribute) 
                    {
                        $variableAttribute = $variableAttributes->get(ucwords(str_replace('_', ' ', $normalizedKey)));
                    }

                    if ($variableAttribute) 
                    {
                        $fieldValue = trim($value);

                        if ($this->isDate($fieldValue)) {
                            $fieldValue = Carbon::parse($fieldValue)->format('Y-m-d'); 
                        }

                        VariableAttributeValue::create([
                            'variable_id' => $variable->variable_id,
                            'variable_attribute_id' => $variableAttribute->variable_attribute_id,
                            'field_value' => $fieldValue,
                        ]);
                    }
                }
            }
        }
    }

    private function normalizeKey($key)
    {
        return strtolower(trim(str_replace(' ', '_', $key)));
    }

    private function isDate($value)
    {
        if (is_numeric($value)) {
            return false;
        }

        try {
            Carbon::parse($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}