<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DataSourceHeadingsExport implements FromView
{
    public function view(): View
    {
        return view('exports.DataSourceHeadings');
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
