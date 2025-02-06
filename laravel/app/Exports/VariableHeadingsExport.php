<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use App\Models\VariableAttribute;
use App\Models\VariableAttributeType;

class VariableHeadingsExport implements WithMultipleSheets
{
    private $variable_type_ids;

    public function __construct($variable_type_ids)
    {
        $this->variable_type_ids = $variable_type_ids;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->variable_type_ids as $variable_type_id) {
            $sheets[] = new VariableHeadingsSheet($variable_type_id);
        }

        return $sheets;
    }
}

class VariableHeadingsSheet implements FromView, WithTitle, WithColumnWidths
{
    private $variable_type_id;
    private $variable_type_name;

    public function __construct($variable_type_id)
    {
        $this->variable_type_id = $variable_type_id;

        $variableType = VariableAttributeType::with('VariableType')->where('variable_type_id', $this->variable_type_id)
            ->first();

        $this->variable_type_name = $variableType ? $variableType->VariableType->variable_type_name : 'Unknown Type';
    }

    public function view(): View
    {
        $variables = VariableAttribute::whereHas('VariableAttributeTypes', function($query) {
            $query->where('variable_type_id', $this->variable_type_id);
        })->get();                  

        $rows = [];

        // headers
        $headers = [
            'Variable Type',
            'Variable Code',
            'Variable Name',
            'Assign To'
        ];

        $values = [
            $this->variable_type_name 
        ];

        foreach ($variables as $variable) {
            array_push($headers, $variable->field_name);
        }

        array_push($rows, $headers);
        array_push($rows, $values);

        return view('exports.VariableHeadings', [
            'rows' => $rows
        ]);
    }

    public function title(): string
    {
        return "Variable Type: " . $this->variable_type_name;
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