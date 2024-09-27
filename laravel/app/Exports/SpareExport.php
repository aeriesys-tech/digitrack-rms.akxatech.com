<?php

namespace App\Exports;

use App\Models\Spare;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class SpareExport implements FromView, WithColumnWidths
{
    private $spares;

    public function __construct()
    {
        $this->spares = Spare::all(); 
    }

    public function view(): View
    {
        return view('exports.Spare', [
            'spares' => $this->spares 
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
