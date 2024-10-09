<?php

namespace App\Exports;

use App\Models\BreakDownAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class BreakDownAttributeExport implements FromView, WithColumnWidths
{
    private $breakdownAttributes;

    public function __construct()
    {
        $this->breakdownAttributes = BreakDownAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.BreakDownAttribute', [
            'break_down_attributes' => $this->breakdownAttributes 
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