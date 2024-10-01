<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use App\Models\SpareAttribute;
use App\Models\SpareAttributeType;

class SpareHeadingsExport implements WithMultipleSheets
{
    private $spare_type_ids;

    public function __construct($spare_type_ids)
    {
        $this->spare_type_ids = $spare_type_ids;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->spare_type_ids as $spare_type_id) {
            $sheets[] = new SpareHeadingsSheet($spare_type_id);
        }

        return $sheets;
    }
}

class SpareHeadingsSheet implements FromView, WithTitle, WithColumnWidths
{
    private $spare_type_id;
    private $spare_type_name;

    public function __construct($spare_type_id)
    {
        $this->spare_type_id = $spare_type_id;

        $spareType = SpareAttributeType::with('SpareType')->where('spare_type_id', $this->spare_type_id)
            ->first();

        // Fetch spare_type_name for display purposes, if needed
        $this->spare_type_name = $spareType ? $spareType->SpareType->spare_type_name : 'Unknown Type';
    }

    public function view(): View
    {
        $spares = SpareAttribute::whereHas('SpareAttributeTypes', function($query) {
            $query->where('spare_type_id', $this->spare_type_id);
        })->get();                  

        $rows = [];

        // headers
        $headers = [
            'Spare Type',
            'Spare Code',
            'Spare Name',
            'Asset Type'
        ];

        // Add the spare type name to the values (optional)
        $values = [
            $this->spare_type_name 
        ];

        foreach ($spares as $spare) {
            array_push($headers, $spare->display_name);
        }

        array_push($rows, $headers);
        array_push($rows, $values);

        return view('exports.SpareHeadings', [
            'rows' => $rows
        ]);
    }

    public function title(): string
    {
        return "Spare Type ID: " . $this->spare_type_id; // Use spare_type_id for the title
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 25,  
            'C' => 25,  
            'D' => 45,  
            'E' => 45,  
            'F' => 45,
            'G' => 45,
            'H' => 45,
            'I' => 45,
            'J' => 45,
            'K' => 45,
            'L' => 45,
            'M' => 45,
            'N' => 45,
            'O' => 45,
            'P' => 45,
            'Q' => 45,
            'R' => 45,
            'S' => 45,
            'T' => 45,
            'U' => 45,
            'V' => 45,
            'W' => 45,
            'X' => 45,
            'Y' => 45,
            'Z' => 45,
            'AA' => 45,
            'AB' => 45,
        ];
    }
}
