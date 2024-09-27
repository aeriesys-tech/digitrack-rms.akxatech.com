<?php

namespace App\Imports;

use App\Models\DataSource;
use App\Models\DataSourceAssetType;
Use App\Models\DataSourceAttributeValue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class DataSourceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $userPlantId = Auth::user()->plant_id;

        foreach ($rows as $row) 
        {
            $data = [
                'data_source_code' => $row['data_source_code'],
                'data_source_name' => $row['data_source_name'],
                'data_source_type_id' => $row['data_source_type_id'],
                'plant_id' => $userPlantId,
            ];

            $data_source = DataSource::create($data);

            if (isset($row['asset_types'])) 
            {
                $assetTypes = explode(',', $row['asset_types']);
                foreach ($assetTypes as $asset_type_id) 
                {
                    DataSourceAssetType::create([
                        'data_source_id' => $data_source->data_source_id,
                        'asset_type_id' => trim($asset_type_id),
                    ]);
                }
            }

            if (isset($row['data_source_attributes'])) 
            {
                $dataSourceAttributes = json_decode($row['data_source_attributes'], true); 

                foreach ($dataSourceAttributes as $attribute) 
                {
                    DataSourceAttributeValue::create([
                        'data_source_id' => $data_source->data_source_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                        'field_value' => $attribute['data_source_attribute_value']['field_value'] ?? '',
                    ]);
                }
            }
        }
    }
}

