<?php

namespace App\Exports;

use App\Models\Check;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class CheckExport implements FromView, WithColumnWidths
{
    private $checks;

    public function __construct()
    {
        $this->checks = Check::all(); 
    }

    public function view(): View
    {
        return view('exports.Check', [
            'checks' => $this->checks 
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
            'H' => 12,
            'I' => 12
        ];
    }
}