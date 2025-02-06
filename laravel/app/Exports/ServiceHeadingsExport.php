<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use App\Models\ServiceAttribute;
use App\Models\ServiceAttributeType;

class ServiceHeadingsExport implements WithMultipleSheets
{
    private $service_type_ids;

    public function __construct($service_type_ids)
    {
        $this->service_type_ids = $service_type_ids;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->service_type_ids as $service_type_id) {
            $sheets[] = new ServiceHeadingsSheet($service_type_id);
        }

        return $sheets;
    }
}

class ServiceHeadingsSheet implements FromView, WithTitle, WithColumnWidths
{
    private $service_type_id;
    private $service_type_name;

    public function __construct($service_type_id)
    {
        $this->service_type_id = $service_type_id;

        $serviceType = ServiceAttributeType::with('ServiceType')->where('service_type_id', $this->service_type_id)->first();

        $this->service_type_name = $serviceType ? $serviceType->ServiceType->service_type_name : 'Unknown Type';
    }

    public function view(): View
    {
        $services = ServiceAttribute::whereHas('ServiceAttributeTypes', function($query) {
            $query->where('service_type_id', $this->service_type_id);
        })->get();                  

        $rows = [];

        // headers
        $headers = [
            'Service Type',
            'Service Code',
            'Service Name',
            'Assign To'
        ];

        $values = [
            $this->service_type_name 
        ];

        foreach ($services as $service) {
            array_push($headers, $service->field_name);
        }

        array_push($rows, $headers);
        array_push($rows, $values);

        return view('exports.ServiceHeadings', [
            'rows' => $rows
        ]);
    }

    public function title(): string
    {
        return "Service Type: " . $this->service_type_name;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 25,  
            'C' => 25,  
            'D' => 45,  
            'E' => 45,  
            'F' => 45,
            'G' => 45,
            'H' => 45,
            'I' => 45,
            'J' => 45,
            'K' => 45,
            'L' => 45,
            'M' => 45,
            'N' => 45,
            'O' => 45,
            'P' => 45,
            'Q' => 45,
            'R' => 45,
            'S' => 45,
            'T' => 45,
            'U' => 45,
            'V' => 45,
            'W' => 45,
            'X' => 45,
            'Y' => 45,
            'Z' => 45,
            'AA' => 45,
            'AB' => 45,
        ];
    }
}