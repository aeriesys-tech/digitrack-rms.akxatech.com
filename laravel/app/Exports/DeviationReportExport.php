<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
                'value' => $item->value ?? '',
                'remark_status' => $item->remark_status? 'Closed' : 'Active'
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
            'Value',
            'Remark Status'
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
            'J' => 18,
            'K' => 18
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE); 
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:K1')->getFill()->getStartColor()->setARGB('0000FF'); 

        $sheet->getStyle('A1:E' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('G1:K' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row = 2;

        foreach ($this->data as $item) {
            $remarkStatus = $item->remark_status ? 'Closed' : 'Active';
            $remarkStatusCell = 'K' . $row;

            if ($remarkStatus === 'Closed') {
                $sheet->getStyle($remarkStatusCell)->getFont()->getColor()->setARGB(Color::COLOR_GREEN);
            } else {
                $sheet->getStyle($remarkStatusCell)->getFont()->getColor()->setARGB(Color::COLOR_RED); 
            }

            $row++;
        }
        return [];
    }
}
