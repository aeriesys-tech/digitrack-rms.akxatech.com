<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
            ['Service No.', 'Service Date', 'Next Service Date', 'Asset Code', 'Asset Zone', 'Service', 'Service Cost', 'Spare', 'Spare Cost'],
        ]);

        $this->services->each(function ($service) use (&$data) {
            $service->UserSpare->each(function ($spare) use ($service, &$data) {
                $data->push([
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
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 35,
            'F' => 25,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
        ];
    }
}

