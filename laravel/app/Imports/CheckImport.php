<?php

namespace App\Imports;

use App\Models\Check;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\models\CheckAssetType;
use App\models\AssetType;
use App\Models\Department;

class CheckImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $department = Department::where('department_name', [$row['department']])->first();
            if($department)
            {
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['Number', 'number']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else
                    {
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Number',
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'ucl' => $row['ucl'],
                            'lcl' => $row['lcl'],
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['Dropdown', 'dropdown','Select', 'select']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else{
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Dropdown',
                            'default_value' => $row['default_value'],
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'field_values' => $row['field_values'],
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['Short Text', 'ShortText', 'Short_Text', 'shorttext']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else
                    {
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Short Text',
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['Long Text', 'LongText', 'Long_Text', 'longtext']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else
                    {
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Long Text',
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['date', 'Date']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else
                    {
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Date',
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
                if (in_array(strtolower(preg_replace('/\s+/', '', $row['field_type'])), ['datetime', 'date&time', 'datetimeandtime', 'dateandtime', 'Date & Time']))
                {
                    $existingCheck = Check::where('field_name', $row['field_name'])->first();
                    if ($existingCheck) {
                        continue;
                    }
                    else
                    {
                        $check = Check::create([
                            'field_name' => $row['field_name'],
                            'field_type' => 'Date & Time',
                            'is_required' => $this->normalizeBoolean($row['is_required']),
                            'order' => $row['order'],
                            'department_id' => $department->department_id
                        ]);   
    
                        if (isset($row['assign_to'])) 
                        {
                            $assetTypeNames = explode(',', $row['assign_to']);
                            $assetTypeDatas = array_map('trim', $assetTypeNames);
                        
                            $assetTypes = AssetType::whereIn('asset_type_name', $assetTypeDatas)->get();
                        
                            if ($assetTypes->isNotEmpty()) 
                            {
                                foreach ($assetTypes as $assetType) {
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
        }
    }

    private function normalizeBoolean($value)
    {
        $normalizedValue = strtolower(trim($value)); 
        if ($normalizedValue === 'yes' || $normalizedValue === 'true' || $normalizedValue === '1') {
            return true;
        }
        if ($normalizedValue === 'no' || $normalizedValue === 'false' || $normalizedValue === '0') {
            return false;
        }
        return false;
    }
}
