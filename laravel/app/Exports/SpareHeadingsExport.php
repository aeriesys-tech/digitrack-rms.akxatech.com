<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\SpareAttribute;

class SpareHeadingsExport implements FromView
{
    private $spare_type_id;

    public function __construct($spare_type_id)
    {
        $this->spare_type_id = $spare_type_id;
    }

    public function view(): View
    {
        $spares = SpareAttribute::whereHas('SpareAttributeTypes', function($query) {
            $query->where('spare_type_id', $this->spare_type_id);
        })->get();

        $rows = [];

        $headers = [
            'Spare Type',
            'Spare Code',
            'Spare Name',
            'Asset Type'
        ];

        foreach ($spares as $spare) {
            array_push($headers, $spare->display_name);
        }

        array_push($rows, $headers);

        return view('exports.SpareHeadings', [
            'rows' => $rows
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 50,  
            'C' => 50,  
            'D' => 30,  
            'E' => 30,  
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 30,
            'M' => 30,
            'N' => 30,
            'O' => 30,
            'P' => 30,
            'Q' => 30,
            'R' => 30,
            'S' => 30,
            'T' => 30,
            'U' => 30,
            'V' => 30,
            'W' => 30,
            'X' => 30,
            'Y' => 30,
            'Z' => 30,
            'AA' => 30,
            'AB' => 30,
        ];
    }
}
