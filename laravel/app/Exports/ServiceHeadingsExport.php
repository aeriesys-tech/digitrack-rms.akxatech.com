<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ServiceHeadingsExport implements FromView
{
    public function view(): View
    {
        return view('exports.ServiceHeadings');
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