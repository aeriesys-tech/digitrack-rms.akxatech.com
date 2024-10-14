<?php

namespace App\Imports;

use App\Models\Check;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\AssetType;
use App\Models\Department;
use App\Models\CheckAssetType;

class CheckImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $department = Department::where('department_name', trim($row['department']))->first();
            $departmentId = $department ? $department->department_id : null;

            $isRequired = strtolower(trim($row['is_required'])) === 'yes' ? 1 : 0;

            $check = Check::create([
                'field_name' => $row['field_name'],
                'field_type' => $row['field_type'],
                'default_value' => $row['default_value'],
                'is_required' => $isRequired,
                'ucl' => $row['ucl'],
                'lcl' => $row['lcl'],
                'field_values' => $row['field_values'],
                'order' => $row['order'],
                'department_id' => $departmentId
            ]); 
            
            if (isset($row['asset_type'])) 
            {
                $assetTypeNames = explode(',', $row['asset_type']);
                $assetTypeNames = array_map('trim', $assetTypeNames);
            
                $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeNames)->get();
            
                if ($assetTypes->isNotEmpty()) 
                {
                    foreach ($assetTypes as $assetType) 
                    {
                        $assetTypeId = $assetType->asset_type_id;
            
                        CheckAssetType::create([
                            'check_id' => $check->check_id,
                            'asset_type_id' => $assetTypeId,
                        ]);
                    }
                } 
            }
        }
    }
}
