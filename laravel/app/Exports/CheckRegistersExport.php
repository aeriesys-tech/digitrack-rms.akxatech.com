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
                'Sl No', 'Asset', 'Reference No.', 'Reference Date', 'Check', 'Field Type', 
                'Default Value', 'Field Values', 'LCL', 'UCL', 'Value'
            ], 
        ]);

        $this->checks->each(function ($check, $key) use (&$data) {
            $check->UserAssetCheck->each(function ($assetCheck) use ($check, &$data, $key) {
                $data->push([
                    $key + 1,
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
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:K1')->getFill()->getStartColor()->setARGB('0000FF');
        
        $sheet->getStyle('A2:K' . ($sheet->getHighestRow()))
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
            'E' => 80,
            'F' => 20,
            'G' => 20,
            'H' => 40,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }
}
