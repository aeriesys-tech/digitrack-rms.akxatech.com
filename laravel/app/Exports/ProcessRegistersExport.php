<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProcessRegistersExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $processes;

    public function __construct($processes)
    {
        $this->processes = $processes;
    }

    public function collection()
    {
        $data = collect([                
            [
                'Sl No', 'Asset', 'Job No', 'Job Date', 'Asset Zone', 'Variable', 'Value'
            ], 
        ]);

        $this->processes->each(function ($variable, $key) use (&$data) {
            $variable->UserAssetVariable->each(function ($assetVariable) use ($variable, &$data, $key) {
                $data->push([
                    $key + 1,
                    $variable->Asset->asset_name ?? '',
                    $variable->job_no,
                    $variable->job_date,
                    $assetVariable->AssetZone->zone_name ?? '',
                    $assetVariable->Variable->variable_name ?? '',
                    $assetVariable->value ?? '',
                ]);
            });
        });

        return $data;
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('0000FF');
        
        $sheet->getStyle('A2:G' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 35,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 35,
            'G' => 20
        ];
    }
}
