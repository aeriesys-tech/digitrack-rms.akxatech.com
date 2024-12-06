<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
            ['Activity No', 'Activity Date & Time', 'Asset Code', 'Equipment', 'Reason', 'Cost'], // Headings
        ])->merge($this->activities->map(function ($activity) {
            return [
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
            'E' => 20,
            'F' => 20,
            'G' => 20,
        ];
    }
}

