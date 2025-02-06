<?php

namespace App\Imports;

use App\Models\Service;
use App\Models\ServiceAssetType;
use App\Models\AssetType;
use App\Models\ServiceAttributeValue;
use App\Models\ServiceAttributeType;
use App\Models\ServiceAttribute;
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

class ServiceImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        $errorRows = [];

        $serviceTypes = ServiceAttributeType::whereHas('ServiceType')->with('ServiceType')->get()
            ->keyBy(function ($serviceAttributeType) {
                return $serviceAttributeType->ServiceType->service_type_name;
            });

        $serviceAttributes = ServiceAttribute::all()->keyBy('field_key');

        foreach ($rows as $index => $row) 
        {
            //Validation
            if (!isset($row['service_code']) || !isset($row['service_name']) || !isset($row['service_type'])) {
                $this->failures[] = new Failure(
                    $index + 1, 
                    'service_code, service_name, spare_type',
                    ['Missing required fields']
                );
                continue; 
            }
            
            if (Spare::where('service_code', trim($row['service_code']))->exists() || 
                Spare::where('service_name', trim($row['service_name']))->exists()) {
                $this->failures[] = new Failure(
                    $index + 1,
                    'service_code, service_name',
                    ['Duplicate service_code or service_name']
                );
                continue;
            }

            $serviceTypeId = $serviceTypes->get(trim($row['service_type'])) ? 
                $serviceTypes->get(trim($row['service_type']))->service_type_id : null;

            $data = [
                'service_type_id' => $serviceTypeId,
                'service_code' => trim($row['service_code']),
                'service_name' => trim($row['service_name']),
            ];

            $service = Service::create($data);

            // asset types
            if (isset($row['assign_to'])) 
            {
                $assetTypeNames = explode(',', $row['assign_to']);
                $assetTypedatas = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypedatas)->get();
            
                if ($assetTypes->isNotEmpty()) {
                    foreach ($assetTypes as $assetType) {
                        $assetTypeId = $assetType->asset_type_id;
            
                        ServiceAssetType::create([
                            'service_id' => $service->service_id,
                            'asset_type_id' => $assetTypeId,
                        ]);
                    }
                } 
            }
           
            foreach ($row as $key => $value) 
            {
                if (!in_array($key, ['service_type', 'service_code', 'service_name', 'assign_to']) && !empty($value)) 
                {
                    $serviceAttribute = $serviceAttributes->get($key); 

                    if ($serviceAttribute) 
                    {
                        $fieldValue = trim($value);
                        $serviceAttributeId = $serviceAttribute->service_attribute_id;
                        if ($serviceAttribute->field_type === 'Color') 
                        {
                            $fieldValue = $this->convertColorNameToHex($fieldValue);
                        }
                        if($serviceAttribute->field_type === 'Date')
                        {
                            $fieldValue = $this->convertDate($fieldValue);
                        }
                        if($serviceAttribute->field_type === 'Date&Time')
                        {
                            $fieldValue = $this->convertDateTime($fieldValue);
                        }

                        ServiceAttributeValue::create([
                            'service_id' => $service->service_id,
                            'service_attribute_id' => $serviceAttributeId,
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