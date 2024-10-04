<?php

namespace App\Imports;

use App\Models\DataSource;
use App\Models\DataSourceAssetType;
Use App\Models\DataSourceAttributeValue;
Use App\Models\DataSourceAttributeType;
Use App\Models\DataSourceAttribute;
Use App\Models\AssetType;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            if (!isset($row['data_source_code']) || !isset($row['data_source_name']) || !isset($row['data_source_type'])) 
            {
                $errorRows[] = $row; 
                continue; 
            }

            $dataSourceTypeId = $dataSourceTypes->get(trim($row['data_source_type'])) ? 
                $dataSourceTypes->get(trim($row['data_source_type']))->data_source_type_id : null;

            $data = [
                'data_source_type_id' => $dataSourceTypeId,
                'data_source_code' => trim($row['data_source_code']),
                'data_source_name' => trim($row['data_source_name']),
            ];

            $dataSource = DataSource::create($data);

            // asset types
            if (isset($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
                if ($assetTypes->isNotEmpty()) {
                    foreach ($assetTypes as $assetType) {
                        $assetTypeId = $assetType->asset_type_id;
            
                        DataSourceAssetType::create([
                            'data_source_id' => $dataSource->data_source_id,
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
                if (!in_array($normalizedKey, ['data_source_type', 'data_source_code', 'data_source_name', 'asset_type']) && !empty($value)) 
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

