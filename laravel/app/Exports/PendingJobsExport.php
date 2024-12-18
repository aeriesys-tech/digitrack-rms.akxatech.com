<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PendingJobsExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    public $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $key) {
            return [
                'sl_no' => $key + 1,
                'service_no' => $item->service_no,
                'service_date' => $item->service_date,
                'service_cost' => $item->service_cost ?? '',
                'next_service_date' => $item->next_service_date,
                'service' => $item->UserSpare->map(fn($spare) => $spare->Service->service_name ?? '')->join(', '),
                'asset' => $item->Asset->asset_name ?? '',
                'spares' => $item->UserSpare->map(fn($spare) => $spare->Spare->spare_name ?? '')->join(', '),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl. No',
            'Service No',
            'Service Date',
            'Service Cost',
            'Next Service Date',
            'Service',
            'Asset',
            'Spares'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 30,
            'G' => 30,
            'H' => 70,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A1:G' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        return [];
    }
}
