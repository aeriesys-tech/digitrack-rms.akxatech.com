<?php

namespace App\Exports;

use App\Models\Variable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class VariableExport implements FromView, WithColumnWidths
{
    private $variables;

    public function __construct()
    {
        $this->variables = Variable::all(); 
    }

    public function view(): View
    {
        return view('exports.Variable', [
            'variables' => $this->variables 
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 35,
            'C' => 14,
            'D' => 14
        ];
    }
}