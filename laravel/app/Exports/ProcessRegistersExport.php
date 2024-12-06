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
                'Asset', 'Job No', 'Job Date', 'Asset Zone', 'Variable', 'Value'
            ], 
        ]);

        $this->processes->each(function ($variable) use (&$data) {
            $variable->UserAssetVariable->each(function ($assetVariable) use ($variable, &$data) {
                $data->push([
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
            'A' => 35,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20
        ];
    }
}
