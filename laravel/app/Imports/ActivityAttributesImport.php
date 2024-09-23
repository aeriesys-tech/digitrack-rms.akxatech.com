<?php

namespace App\Imports;

use App\Models\ActivityAttribute;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ActivityAttributesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            ActivityAttribute::create([
                'field_name' => $row['field_name'],
                'display_name' => $row['display_name'],
                'field_type' => $row['field_type'],
                'field_values' => $row['field_values'],
                'field_length' => $row['field_length'],
                'is_required' => $row['is_required'],
                'user_id' => $row['user_id'],
                'list_parameter_id' => $row['list_parameter_id']
            ]);   
        }
    }
}