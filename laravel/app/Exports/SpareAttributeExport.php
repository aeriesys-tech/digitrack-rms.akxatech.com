<?php

namespace App\Exports;

use App\Models\SpareAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class SpareAttributeExport implements FromView, WithColumnWidths
{
    private $spareAttributes;

    public function __construct()
    {
        $this->spareAttributes = SpareAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.SpareAttribute', [
            'spare_attributes' => $this->spareAttributes 
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