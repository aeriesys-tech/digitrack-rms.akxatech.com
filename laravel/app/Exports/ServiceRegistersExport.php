<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ServiceRegistersExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $services;
   
    public function __construct($services)
    {
        $this->services = $services;
    }

    public function collection()
    {
        $data = collect([
            ['Sl No','Service No.', 'Service Date', 'Next Service Date', 'Asset Code', 'Asset Zone', 'Service', 'Service Cost', 'Spare', 'Spare Cost'],
        ]);

        $this->services->each(function ($service, $key) use (&$data) {
            $service->UserSpare->each(function ($spare) use ($service, &$data, $key) {
                $data->push([
                    $key + 1,
                    $service->service_no,
                    $service->service_date,
                    $service->next_service_date ?? '',
                    $service->Asset->asset_code,
                    $spare->AssetZone->zone_name ?? '',
                    $spare->Service->service_name ?? '',
                    $spare->service_cost ?? '',
                    $spare->Spare->spare_name ?? '',
                    $spare->spare_cost
                ]);
            });
        });

        return $data; 
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A2:J' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 35,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 20
        ];
    }
}

