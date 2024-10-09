<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use App\Models\DataSourceAttribute;
use App\Models\DataSourceAttributeType;

class DataSourceHeadingsExport implements WithMultipleSheets
{
    private $data_source_type_ids;

    public function __construct($data_source_type_ids)
    {
        $this->data_source_type_ids = $data_source_type_ids;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data_source_type_ids as $data_source_type_id) {
            $sheets[] = new DataSourceHeadingsSheet($data_source_type_id);
        }

        return $sheets;
    }
}

class DataSourceHeadingsSheet implements FromView, WithTitle, WithColumnWidths
{
    private $data_source_type_id;
    private $data_source_type_name;

    public function __construct($data_source_type_id)
    {
        $this->data_source_type_id = $data_source_type_id;

        $data_sourceType = DataSourceAttributeType::with('DataSourceType')->where('data_source_type_id', $this->data_source_type_id)->first();

        $this->data_source_type_name = $data_sourceType ? $data_sourceType->DataSourceType->data_source_type_name : 'Unknown Type';
    }

    public function view(): View
    {
        $data_sources = DataSourceAttribute::whereHas('DataSourceAttributeTypes', function($query) {
            $query->where('data_source_type_id', $this->data_source_type_id);
        })->get();                  

        $rows = [];

        // headers
        $headers = [
            'DataSource Type',
            'DataSource Code',
            'DataSource Name',
            'Asset Type'
        ];

        $values = [
            $this->data_source_type_name 
        ];

        foreach ($data_sources as $data_source) {
            array_push($headers, $data_source->field_name);
        }

        array_push($rows, $headers);
        array_push($rows, $values);

        return view('exports.DataSourceHeadings', [
            'rows' => $rows
        ]);
    }

    public function title(): string
    {
        return "DataSource Type: " . $this->data_source_type_name;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 25,  
            'C' => 25,  
            'D' => 45,  
            'E' => 45,  
            'F' => 45,
            'G' => 45,
            'H' => 45,
            'I' => 45,
            'J' => 45,
            'K' => 45,
            'L' => 45,
            'M' => 45,
            'N' => 45,
            'O' => 45,
            'P' => 45,
            'Q' => 45,
            'R' => 45,
            'S' => 45,
            'T' => 45,
            'U' => 45,
            'V' => 45,
            'W' => 45,
            'X' => 45,
            'Y' => 45,
            'Z' => 45,
            'AA' => 45,
            'AB' => 45,
        ];
    }
}