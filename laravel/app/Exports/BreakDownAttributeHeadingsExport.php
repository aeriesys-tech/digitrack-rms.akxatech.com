<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class BreakDownAttributeHeadingsExport implements FromView
{
    public function view(): View
    {
        return view('exports.BreakDownAttributeHeadings');
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