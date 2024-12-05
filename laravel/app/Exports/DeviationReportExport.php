<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeviationReportExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $key) 
        {
            $departments = $item->UserCheck->Asset->Department->pluck('department_name')->join(', ');

            return [
                'sl_no' => $key + 1,
                'department' => $departments ?? '',
                'asset' => $item->UserCheck->Asset->asset_name ?? '',
                'asset type' => $item->UserCheck->Asset->AssetType->asset_type_name ?? '',
                'check date' => $item->UserCheck->reference_date,
                'check' => $item->Check->field_name ?? '',
                'lcl' => $item->lcl ?? '',
                'ucl' => $item->ucl ?? '',
                'default value' => $item->default_value ?? '',
                'value' => $item->value ?? ''
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl. No',
            'Department',
            'Asset',
            'Asset Type',
            'Check Date',
            'Check',
            'Lcl',
            'Ucl',
            'Default Value',
            'Value'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 40,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 100,
            'G' => 20,
            'H' => 10,
            'I' => 20,
            'J' => 10
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true
                ]
            ]
        ];
    }
}
