<?php

namespace App\Exports;

use App\Models\Variable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class VariableExport implements FromView, WithColumnWidths
{
    //private $variables;

    public function __construct()
    {
    }

    public function view(): View
    {
        $variables = Variable::all(); 
        $rows = []; 

        foreach ($variables as $key => $variable) 
        {
            $variableValues = $variable->getVariableValues($variable->variable_id);

            $headers = [
                'Sl No',
                'Variable Code',
                'Variable Name',
                'Variable Type',
                'Asset Type'
            ];
            
            $cell_values = [
                $key + 1,
                $variable['variable_code'], 
                $variable['variable_name'],
                $variable->VariableType['variable_type_name'],
                optional($variable->VariableAssetTypes)->map(function($assetType) {
                    return $assetType->AssetType['asset_type_name']; 
                })->implode(', ')
            ];

            foreach ($variableValues as $attributeValue) 
            {
                array_push($headers, $attributeValue['VariableAttributeData']['display_name']); 
                array_push($cell_values, $attributeValue['field_value']); 
            }

            array_push($rows, $headers);
            array_push($rows, $cell_values);
        }

        return view('exports.Variable', [
            'rows' => $rows
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
            'C' => 25,
            'D' => 25,
            'E' => 45,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
            'L' => 25,
            'M' => 25,
            'N' => 25,
            'O' => 25,
            'P' => 25,
            'Q' => 25,
            'R' => 25,
            'S' => 25,
            'T' => 25,
            'U' => 25,
            'V' => 25,
            'W' => 25,
            'X' => 25,
            'Y' => 25,
            'Z' => 25,
            'AA' => 25,
            'AB' => 25,
        ];
    }
}