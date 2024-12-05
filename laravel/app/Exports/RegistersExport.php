<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistersExport implements WithMultipleSheets
{
    protected $activities;
    protected $services;
    protected $checks;
    protected $processes;

    public function __construct($activities, $services, $checks, $processes)
    {
        $this->activities = $activities;
        $this->services = $services;
        $this->checks = $checks;
        $this->processes = $processes;
    }

    public function sheets(): array
    {
        return [
            'Activity Register' => new ActivitiesSheet($this->activities),
            'Service Register' => new ServicesSheet($this->services),
            'Check Register' => new ChecksSheet($this->checks),
            'Process Register' => new ProcessSheet($this->processes),
        ];
    }
}

class ActivitiesSheet implements FromCollection, WithStyles, WithColumnWidths
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return collect([
            ['Activity Registers'], 
            [],                  
            ['Activity No', 'Activity Date & Time', 'Asset Code', 'Reason', 'Cost', 'Status'], 
        ])->merge($this->activities->map(function ($activity) {
            return [
                $activity->activity_no,
                $activity->activity_date,
                $activity->Asset->asset_code,
                $activity->Reason->reason_name ?? '',
                $activity->cost ?? '',
                $activity->activity_status ?? '',
            ];
        }));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ 
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [ 
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

class ServicesSheet implements FromCollection, WithStyles, WithColumnWidths
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function collection()
    {
        $data = collect([
            ['Service Registers'], 
            [], 
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
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [ 
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
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20
        ];
    }
}

class ChecksSheet implements FromCollection, WithStyles, WithColumnWidths
{
    protected $checks;

    public function __construct($checks)
    {
        $this->checks = $checks;
    }

    public function collection()
    {
        $data = collect([
            ['Check Registers'], 
            [],                 
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
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [
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

class ProcessSheet implements FromCollection, WithStyles, WithColumnWidths
{
    protected $processes;

    public function __construct($processes)
    {
        $this->processes = $processes;
    }

    public function collection()
    {
        $data = collect([
            ['Process Registers'], 
            [],                 
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
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [
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
