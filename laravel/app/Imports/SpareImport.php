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

class SpareImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errorRows = [];
  
        $assetTypes = SpareAssetType::whereHas('AssetType')->with('AssetType')->get()
            ->keyBy(function ($spareAssetType) {
                return $spareAssetType->AssetType->asset_type_name;
            });

        $spareTypes = SpareAttributeType::whereHas('SpareType')->with('SpareType')->get()
            ->keyBy(function ($spareAttributeType) {
                return $spareAttributeType->SpareType->spare_type_name;
            });

        $spareAttributes = SpareAttribute::all()->keyBy('field_name');

        foreach ($rows as $row) 
        {
            if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type'])) 
            {
                $errorRows[] = $row; 
                continue; 
            }

            $spareTypeId = $spareTypes->get(trim($row['spare_type'])) ? 
                $spareTypes->get(trim($row['spare_type']))->spare_type_id : null;

            $data = [
                'spare_type_id' => $spareTypeId,
                'spare_code' => trim($row['spare_code']),
                'spare_name' => trim($row['spare_name']),
            ];

            $spare = Spare::create($data);

            // asset types
            if (isset($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
                if ($assetTypes->isNotEmpty()) {
                    foreach ($assetTypes as $assetType) {
                        $assetTypeId = $assetType->asset_type_id;
            
                        SpareAssetType::create([
                            'spare_id' => $spare->spare_id,
                            'asset_type_id' => $assetTypeId,
                        ]);
                    }
                } 
            }

            foreach ($row as $key => $value) 
            {
                $normalizedKey = $this->normalizeKey($key);

                // Skip the known keys like spare_type, spare_code, spare_name, and asset_type
                if (!in_array($normalizedKey, ['spare_type', 'spare_code', 'spare_name', 'asset_type']) && !empty($value)) 
                {
                    // Fetch spare attribute using normalized key
                    $spareAttribute = $spareAttributes->get($normalizedKey); 
                    if (!$spareAttribute) 
                    {
                        // Attempt to find the attribute using a different normalization method (e.g., title casing)
                        $spareAttribute = $spareAttributes->get(ucwords(str_replace('_', ' ', $normalizedKey)));
                    }

                    if ($spareAttribute) 
                    {
                        $fieldValue = trim($value);

                        // Process the field value for number or date types
                        if (is_numeric($fieldValue)) {
                            $fieldValue = $fieldValue;
                        }

                        if ($this->isDate($fieldValue)) {
                            $fieldValue = Carbon::parse($fieldValue);
                        }

                        //SpareAttributeValue
                        SpareAttributeValue::create([
                            'spare_id' => $spare->spare_id,
                            'spare_attribute_id' => $spareAttribute->spare_attribute_id,
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
