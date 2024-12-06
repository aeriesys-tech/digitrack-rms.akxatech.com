<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BreakDownRegistersExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $breaks;

    public function __construct($breaks)
    {
        $this->breaks = $breaks;
    }

    public function collection()
    {
        return collect([                  
            ['Job No', 'Job Date', 'BreakDown Type', 'Asset'],
        ])->merge($this->breaks->map(function ($break) {
            return [
                $break->job_no,
                $break->job_date,
                $break->BreakDownType->break_down_type_name ?? '',
                $break->Asset->asset_name ?? '',
            ];
        }));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ 
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0000FF']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 30,
            'D' => 20,
            'E' => 20
        ];
    }
}

