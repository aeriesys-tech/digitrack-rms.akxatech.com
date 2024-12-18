<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            ['Sl No','Job No', 'Job Date', 'BreakDown Type', 'Asset'],
        ])->merge($this->breaks->map(function ($break, $key) {
            return [
                $key + 1,
                $break->job_no,
                $break->job_date,
                $break->BreakDownType->break_down_type_name ?? '',
                $break->Asset->asset_name ?? '',
            ];
        }));
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A1:E' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 25,
            'C' => 30,
            'D' => 40,
            'E' => 20
        ];
    }
}

