<?php

namespace App\Imports;

use App\Models\DataSource;
use App\Models\DataSourceAssetType;
use App\Models\DataSourceAttributeValue;
use App\Models\DataSourceAttributeType;
use App\Models\DataSourceAttribute;
use App\Models\AssetType;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DataSourceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errorRows = [];
  
        $assetTypes = DataSourceAssetType::whereHas('AssetType')->with('AssetType')->get()
            ->keyBy(function ($datasourceAssetType) {
                return $datasourceAssetType->AssetType->asset_type_name;
            });

        $dataSourceTypes = DataSourceAttributeType::whereHas('DataSourceType')->with('DataSourceType')->get()
            ->keyBy(function ($dataSourceAttributeType) {
                return $dataSourceAttributeType->DataSourceType->data_source_type_name;
            });

        $DataSourceAttributes = DataSourceAttribute::all()->keyBy('field_name');

        foreach ($rows as $row) 
        {
            // Log::info('Processing row: ', $row->toArray());

            if (!isset($row['datasource_code']) || !isset($row['datasource_name']) || !isset($row['datasource_type'])) 
            {
                $errorRows[] = $row; 
                continue; 
            }

            $dataSourceTypeId = $dataSourceTypes->get(trim($row['datasource_type'])) 
                ? $dataSourceTypes->get(trim($row['datasource_type']))->data_source_type_id 
                : null;

            $data = [
                'data_source_type_id' => $dataSourceTypeId,
                'data_source_code' => trim($row['datasource_code']),
                'data_source_name' => trim($row['datasource_name']),
            ];
            // Log::info('Inserting Data Source: ', $data);
            $dataSource = DataSource::create($data);

            // Handling asset types
            if (isset($row['asset_type']) && !empty($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
                if ($assetTypes->isNotEmpty()) {
                    foreach ($assetTypes as $assetType) {
                        DataSourceAssetType::create([
                            'data_source_id' => $dataSource->data_source_id,
                            'asset_type_id' => $assetType->asset_type_id,
                        ]);
                        Log::info('Asset Type linked: ' . $assetType->asset_type_name);
                    }
                } 
            }

            // Handling dynamic attributes
            foreach ($row as $key => $value) 
            {
                $normalizedKey = $this->normalizeKey($key);

                if (!in_array($normalizedKey, ['datasource_type', 'datasource_code', 'datasource_name', 'asset_type']) && !empty($value)) 
                {
                    $dataSourceAttribute = $DataSourceAttributes->get($normalizedKey); 
                    if (!$dataSourceAttribute) 
                    {
                        $dataSourceAttribute = $DataSourceAttributes->get(ucwords(str_replace('_', ' ', $normalizedKey)));
                    }

                    if ($dataSourceAttribute) 
                    {
                        $fieldValue = trim($value);

                        if ($this->isDate($fieldValue)) {
                            $fieldValue = Carbon::parse($fieldValue)->format('Y-m-d'); 
                        }

                        DataSourceAttributeValue::create([
                            'data_source_id' => $dataSource->data_source_id,
                            'data_source_attribute_id' => $dataSourceAttribute->data_source_attribute_id,
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
