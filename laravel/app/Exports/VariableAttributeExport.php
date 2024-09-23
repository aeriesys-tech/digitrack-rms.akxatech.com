<?php

namespace App\Exports;

use App\Models\VariableAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class VariableAttributeExport implements FromView, WithColumnWidths
{
    private $variableAttributes;

    public function __construct()
    {
        $this->variableAttributes = VariableAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.VariableAttribute', [
            'variable_attributes' => $this->variableAttributes 
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