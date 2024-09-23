<?php

namespace App\Exports;

use App\Models\DataSourceAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class DataSourceAttributeExport implements FromView, WithColumnWidths
{
    private $dataSourceAttributes;

    public function __construct()
    {
        $this->dataSourceAttributes = DataSourceAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.DataSourceAttribute', [
            'data_source_attributes' => $this->dataSourceAttributes 
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