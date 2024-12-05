<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckRegistersExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $checks;

    public function __construct($checks)
    {
        $this->checks = $checks;
    }

    public function collection()
    {
        $data = collect([             
            [
                'Asset', 'Reference No.', 'Reference Date', 'Check', 'Field Type', 
                'Default Value', 'Field Values', 'LCL', 'UCL', 'Value'
            ], 
        ]);

        $this->checks->each(function ($check) use (&$data) {
            $check->UserAssetCheck->each(function ($assetCheck) use ($check, &$data) {
                $data->push([
                    $check->Asset->asset_name ?? '',
                    $check->reference_no,
                    $check->reference_date,
                    $assetCheck->field_name ?? '',
                    $assetCheck->field_type ?? '',
                    $assetCheck->default_value ?? '',
                    $assetCheck->field_values ?? '',
                    $assetCheck->lcl ?? '',
                    $assetCheck->ucl ?? '',
                    $assetCheck->value ?? '',
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
            'D' => 80,
            'E' => 20,
            'F' => 20,
            'G' => 40,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }
}
