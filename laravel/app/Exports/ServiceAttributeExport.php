<?php

namespace App\Exports;

use App\Models\ServiceAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class ServiceAttributeExport implements FromView, WithColumnWidths
{
    private $serviceAttributes;

    public function __construct()
    {
        $this->serviceAttributes = ServiceAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.ServiceAttribute', [
            'service_attributes' => $this->serviceAttributes 
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 35,
            'C' => 14,
            'D' => 14,
            'E' => 12,
            'F' => 14,
            'G' => 12,
            'H' => 12
        ];
    }
}