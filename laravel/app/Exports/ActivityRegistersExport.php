<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ActivityRegistersExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return collect([                  
            ['Sl No', 'Activity No', 'Activity Date & Time', 'Asset Code', 'Equipment', 'Reason', 'Cost'], // Headings
        ])->merge($this->activities->map(function ($activity, $key) {
            return [
                $key + 1,
                $activity->activity_no,
                $activity->activity_date,
                $activity->Asset->asset_code,
                $activity->Equipment->equipment_name ?? '',
                $activity->Reason->reason_name ?? '',
                $activity->cost ?? '',
            ];
        }));
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('0000FF');
        
        // Center align for all rows (excluding header row)
        $sheet->getStyle('A2:G' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 20,
            'C' => 25,
            'D' => 30,
            'E' => 20,
            'F' => 20,
            'G' => 20,
        ];
    }
}

