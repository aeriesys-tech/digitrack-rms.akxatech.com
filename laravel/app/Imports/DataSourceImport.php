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
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;

class DataSourceImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
  
        $dataSourceTypes = DataSourceAttributeType::whereHas('DataSourceType')->with('DataSourceType')->get()
            ->keyBy(function ($dataSourceAttributeType) {
                return $dataSourceAttributeType->DataSourceType->data_source_type_name;
            });

        $DataSourceAttributes = DataSourceAttribute::all()->keyBy('field_key');

        foreach ($rows as $index => $row) 
        {
            if (!isset($row['data_source_code']) || !isset($row['data_source_name'])) {
                $this->failures[] = new Failure(
                    $index + 1, 
                    'data_source_code, data_source_name, spare_type',
                    ['Missing required fields']
                );
                continue; 
            }
            
            if (DataSource::where('data_source_code', trim($row['data_source_code']))->exists() || 
                DataSource::where('data_source_name', trim($row['data_source_name']))->exists()) {
                $this->failures[] = new Failure(
                    $index + 1,
                    'data_source_code, data_source_name',
                    ['Duplicate data_source_code or data_source_name']
                );
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
            if (isset($row['assign_to'])) 
            {
                $assetTypeNames = array_map('trim', explode(',', $row['assign_to']));
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

            foreach ($row as $key => $value) 
            {
                if (!in_array($key, ['data_source_type', 'data_source_code', 'data_source_name', 'assign_to']) && !empty($value)) 
                {
                    $dataSourceAttribute = $DataSourceAttributes->get($key); 
                
                    if ($dataSourceAttribute) 
                    {
                        $fieldValue = trim($value);
                        $dataSourceAttributeId = $dataSourceAttribute->data_source_attribute_id;

                        if ($dataSourceAttribute->field_type === 'Color') 
                        {
                            $fieldValue = $this->convertColorNameToHex($fieldValue);
                        }
                        if($dataSourceAttribute->field_type === 'Date')
                        {
                            $fieldValue = $this->convertDate($fieldValue);
                        }
                        if($dataSourceAttribute->field_type === 'Date&Time')
                        {
                            $fieldValue = $this->convertDateTime($fieldValue);
                        }

                        DataSourceAttributeValue::create([
                            'data_source_id' => $dataSource->data_source_id,
                            'data_source_attribute_id' => $dataSourceAttributeId,
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

