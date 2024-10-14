<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class CheckHeadingsExport implements FromView, WithColumnWidths
{
    public function view(): View
    {
        return view('exports.CheckHeadings');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 14,
            'C' => 14,
            'D' => 14,
            'E' => 12,
            'F' => 14,
            'G' => 25,
            'H' => 12,
            'I' => 18,
            'J' => 30
        ];
    }
}

