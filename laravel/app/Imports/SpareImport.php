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
    // public function collection(Collection $rows)
    // {
    //     $errorRows = [];

    //     // Fetch Asset Types and Spare Types to map names to IDs
    //     $assetTypes = SpareAssetType::whereHas('AssetType')->with('AssetType')->get()
    //         ->keyBy(function ($spareAssetType) {
    //             return $spareAssetType->AssetType->asset_type_name;
    //         });

    //     // dd($assetTypes);

    //     $spareTypes = SpareAttributeType::whereHas('SpareType')->with('SpareType')->get()
    //         ->keyBy(function ($spareAttributeType) {
    //             return $spareAttributeType->SpareType->spare_type_name;
    //         });

    //     $spareAttributes = SpareAttribute::all()->keyBy('display_name');

    //     // dd(type($rows));
    //     // dd(gettype($rows));

    //     foreach ($rows as $row) 
    //     {
            
    //         if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type'])) 
    //         {
    //             $errorRows[] = $row; 
    //             continue; 
    //         }

    //         $data = [
    //             'spare_type_id' => $spareTypes->get(trim($row['spare_type'])) ? 
    //                 $spareTypes->get(trim($row['spare_type']))->spare_type_id : null,
    //             'spare_code' => trim($row['spare_code']),
    //             'spare_name' => trim($row['spare_name']),
    //         ];

    //         // Log::info($row['asset_type']);
    //         $spare = Spare::create($data);

    //         if (isset($row['asset_type'])) 
    //         {
    //             $assetTypeNames = explode(',', $row['asset_type']);
    //             $assetTypeNames = array_map('trim', $assetTypeNames);
            
    //             $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
    //             if ($assetTypes->isNotEmpty()) {
    //                 foreach ($assetTypes as $assetType) {
    //                     $assetTypeId = $assetType->asset_type_id;
            
    //                     SpareAssetType::create([
    //                         'spare_id' => $spare->spare_id,
    //                         'asset_type_id' => $assetTypeId,
    //                     ]);
    //                 }
    //             } 
    //         }
    //         foreach ($row as $key => $value) 
    //         {
    //             if (!in_array($key, ['spare_type', 'spare_code', 'spare_name', 'asset_type']) && !empty($value)) {
    //                 $spareAttribute = $spareAttributes->get(trim($key));
    //                 if ($spareAttribute) {
    //                     SpareAttributeValue::create([
    //                         'spare_id' => $spare->spare_id,
    //                         'spare_attribute_id' => $spareAttribute->spare_attribute_id,
    //                         'field_value' => trim($value),
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }

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

        $spareAttributes = SpareAttribute::all()->keyBy('display_name');

        foreach ($rows as $row) 
        {
            if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type'])) 
            {
                $errorRows[] = $row; 
                continue; 
            }

            $spareTypeId = $spareTypes->get(trim($row['spare_type'])) ? 
                $spareTypes->get(trim($row['spare_type']))->spare_type_id : null;

            // if ($spareTypeId === null) {
            //     Log::error('Spare Type ID is null for spare type: ' . trim($row['spare_type']));
            //     $errorRows[] = $row; 
            //     continue;
            // }

            $data = [
                'spare_type_id' => $spareTypeId,
                'spare_code' => trim($row['spare_code']),
                'spare_name' => trim($row['spare_name']),
            ];

            $spare = Spare::create($data);

            // Handle asset types
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
            
            // Handle spare attributes
            foreach ($row as $key => $value) 
            {
                if (!in_array($key, ['spare_type', 'spare_code', 'spare_name', 'asset_type']) && !empty($value)) {
                    $spareAttribute = $spareAttributes->get(trim($key));
                    if ($spareAttribute) {
                        SpareAttributeValue::create([
                            'spare_id' => $spare->spare_id,
                            'spare_attribute_id' => $spareAttribute->spare_attribute_id,
                            'field_value' => trim($value),
                        ]);
                    }
                }
            }
        }
    }
}
