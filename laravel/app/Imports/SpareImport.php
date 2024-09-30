<?php

// namespace App\Imports;

// use App\Models\Spare;
// use App\Models\SpareAssetType;
// use App\Models\SpareAttributeValue;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Auth;

// class SpareImport implements ToCollection, WithHeadingRow
// {
//     public function collection(Collection $rows)
//     {
//         $userPlantId = Auth::user()->plant_id;

//         foreach ($rows as $row) 
//         {
//             if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type_id'])) 
//             {
//                 continue; 
//             }

//             $data = [
//                 'spare_code' => $row['spare_code'],
//                 'spare_name' => $row['spare_name'],
//                 'spare_type_id' => $row['spare_type_id'],
//                 'plant_id' => $userPlantId,
//             ];

//             $spare = Spare::create($data);

//             if (isset($row['asset_types'])) 
//             {
//                 $assetTypes = explode(',', $row['asset_types']);
//                 foreach ($assetTypes as $asset_type_id) 
//                 {
//                     SpareAssetType::create([
//                         'spare_id' => $spare->spare_id,
//                         'asset_type_id' => trim($asset_type_id),
//                     ]);
//                 }
//             }

//             if (isset($row['spare_attributes'])) 
//             {
//                 $spareAttributes = json_decode($row['spare_attributes'], true); 

//                 foreach ($spareAttributes as $attribute) 
//                 {
//                     if (isset($attribute['spare_attribute_id'], $attribute['spare_attribute_value']['field_value'])) 
//                     {
//                         SpareAttributeValue::create([
//                             'spare_id' => $spare->spare_id,
//                             'spare_attribute_id' => $attribute['spare_attribute_id'],
//                             'field_value' => $attribute['spare_attribute_value']['field_value'],
//                         ]);
//                     }
//                 }
//             }
//         }
//     }
// }

namespace App\Imports;

use App\Models\Spare;
use App\Models\SpareAssetType;
use App\Models\SpareAttributeValue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use Illuminate\Support\Facades\Log;

class SpareImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $userPlantId = Auth::user()->plant_id;
        $successCount = 0;
        $errorRows = [];

        foreach ($rows as $row) 
        {
            if (!isset($row['spare_code']) || !isset($row['spare_name']) || !isset($row['spare_type_id'])) 
            {
                $errorRows[] = $row; // Store invalid rows for logging
                continue; 
            }

            $data = [
                'spare_code' => $row['spare_code'],
                'spare_name' => $row['spare_name'],
                'spare_type_id' => $row['spare_type_id'],
                'plant_id' => $userPlantId,
            ];

            try {
                $spare = Spare::create($data);
                $successCount++;

                // Handle asset types
                if (isset($row['asset_types'])) 
                {
                    $assetTypes = explode(',', $row['asset_types']);
                    foreach ($assetTypes as $asset_type_id) 
                    {
                        SpareAssetType::create([
                            'spare_id' => $spare->spare_id,
                            'asset_type_id' => trim($asset_type_id),
                        ]);
                    }
                }

                // Handle spare attributes
                if (isset($row['spare_attributes'])) 
                {
                    $spareAttributes = json_decode($row['spare_attributes'], true); 
                    foreach ($spareAttributes as $attribute) 
                    {
                        if (isset($attribute['spare_attribute_id'], $attribute['spare_attribute_value']['field_value'])) 
                        {
                            SpareAttributeValue::create([
                                'spare_id' => $spare->spare_id,
                                'spare_attribute_id' => $attribute['spare_attribute_id'],
                                'field_value' => $attribute['spare_attribute_value']['field_value'],
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error importing spare: ' . $e->getMessage(), ['row' => $row]);
                $errorRows[] = $row; // Store failed row for error reporting
            }
        }

        // Optional: Log success and errors
        Log::info('Spare import completed. Success: ' . $successCount . ', Errors: ' . count($errorRows));

        // Optionally return the success/error counts or detailed reports
    }
}
