<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            ['Sl No', 'Activity No', 'Activity Date & Time', 'Asset Code', 'Reason', 'Cost', 'Status'], 
        ])->merge($this->activities->map(function ($activity, $key) {
            return [
                $key + 1,
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
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A2:G2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:G2')->getFill()->getStartColor()->setARGB('0000FF');
        
        // Center align for all rows (excluding header row)
        $sheet->getStyle('A3:G' . ($sheet->getHighestRow()))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 20,
            'C' => 25,
            'D' => 30,
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
            ['Sl No', 'Service No.', 'Service Date', 'Next Service Date', 'Asset Code', 'Asset Zone', 'Service', 'Service Cost', 'Spare', 'Spare Cost'],
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
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);
        $sheet->getStyle('A2:J2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A2:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:J2')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A3:J' . ($sheet->getHighestRow()))
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
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20
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
        $sheet->getStyle('A2:K2')->getFont()->setBold(true);
        $sheet->getStyle('A2:K2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:K2')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A3:K' . ($sheet->getHighestRow()))
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
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A2:G2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:G2')->getFill()->getStartColor()->setARGB('0000FF');
    
        $sheet->getStyle('A3:G' . ($sheet->getHighestRow()))
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
            'F' => 40,
            'G' => 20
        ];
    }
}
