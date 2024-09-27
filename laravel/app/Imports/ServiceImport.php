<?php

namespace App\Imports;

use App\Models\Service;
use App\Models\ServiceAssetType;
Use App\Models\ServiceAttributeValue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class ServiceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $userPlantId = Auth::user()->plant_id;

        foreach ($rows as $row) 
        {
            $data = [
                'service_code' => $row['service_code'],
                'service_name' => $row['service_name'],
                'service_type_id' => $row['service_type_id'],
                'plant_id' => $userPlantId,
            ];

            $service = Service::create($data);

            if (isset($row['asset_types'])) 
            {
                $assetTypes = explode(',', $row['asset_types']);
                foreach ($assetTypes as $asset_type_id) 
                {
                    ServiceAssetType::create([
                        'service_id' => $service->service_id,
                        'asset_type_id' => trim($asset_type_id),
                    ]);
                }
            }

            if (isset($row['service_attributes'])) 
            {
                $serviceAttributes = json_decode($row['service_attributes'], true); 

                foreach ($serviceAttributes as $attribute) 
                {
                    ServiceAttributeValue::create([
                        'service_id' => $service->service_id,
                        'service_attribute_id' => $attribute['service_attribute_id'],
                        'field_value' => $attribute['service_attribute_value']['field_value'] ?? '',
                    ]);
                }
            }
        }
    }
}