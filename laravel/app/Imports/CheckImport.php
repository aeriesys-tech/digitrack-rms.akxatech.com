<?php

namespace App\Imports;

use App\Models\Check;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CheckImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Check::create([
                'field_name' => $row['field_name'],
                'field_type' => $row['field_type'],
                'default_value' => $row['default_value'],
                'is_required' => $row['is_required'],
                'ucl' => $row['ucl'],
                'lcl' => $row['lcl'],
                'field_values' => $row['field_values'],
                'order' => $row['order'],
                'department_id' => $row['department_id']
            ]);   
        }
    }
}
