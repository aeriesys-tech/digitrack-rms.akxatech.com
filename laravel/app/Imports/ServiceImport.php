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

class ServiceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errorRows = [];
  
        $assetTypes = ServiceAssetType::whereHas('AssetType')->with('AssetType')->get()
            ->keyBy(function ($spareAssetType) {
                return $spareAssetType->AssetType->asset_type_name;
            });

        $serviceTypes = ServiceAttributeType::whereHas('ServiceType')->with('ServiceType')->get()
            ->keyBy(function ($serviceAttributeType) {
                return $serviceAttributeType->ServiceType->service_type_name;
            });

        $serviceAttributes = ServiceAttribute::all()->keyBy('field_name');

        foreach ($rows as $row) 
        {
            if (!isset($row['service_code']) || !isset($row['service_name']) || !isset($row['service_type'])) 
            {
                $errorRows[] = $row; 
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
            if (isset($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
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
            Log::info($row);
            foreach ($row as $key => $value) 
            {
                $normalizedKey = $this->normalizeKey($key);

                // Skip the known keys like spare_type, spare_code, spare_name, and asset_type
                if (!in_array($normalizedKey, ['service_type', 'service_code', 'service_name', 'asset_type']) && !empty($value)) 
                {
                    $serviceAttribute = $serviceAttributes->get($normalizedKey); 
                    if (!$serviceAttribute) 
                    {
                        $serviceAttribute = $serviceAttributes->get(ucwords(str_replace('_', ' ', $normalizedKey)));
                    }

                    if ($serviceAttribute) 
                    {
                        $fieldValue = trim($value);

                        if ($this->isDate($fieldValue)) {
                            $fieldValue = Carbon::parse($fieldValue)->format('Y-m-d'); 
                        }

                        ServiceAttributeValue::create([
                            'service_id' => $service->service_id,
                            'service_attribute_id' => $serviceAttribute->service_attribute_id,
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