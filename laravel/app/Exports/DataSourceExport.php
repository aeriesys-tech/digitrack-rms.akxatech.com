<?php

namespace App\Exports;

use App\Models\DataSource;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class DataSourceExport implements FromView, WithColumnWidths
{
    private $data_sources;

    public function __construct()
    {
        $this->data_sources = DataSource::all();
    }

    public function view(): View
    {
        return view('exports.DataSource', [
            'data_sources' => $this->data_sources
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