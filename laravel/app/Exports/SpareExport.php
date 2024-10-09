<?php

namespace App\Exports;

use App\Models\Spare;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class SpareExport implements FromView, WithColumnWidths
{
    // private $spares;

    public function __construct()
    {
     
    }

    public function view(): View
    {
        $spares = Spare::all(); 
        $rows = []; 

        foreach ($spares as $key => $spare) 
        {
            $spareValues = $spare->getSpareValues($spare->spare_id);

            $headers = [
                'Sl No',
                'Spare Code',
                'Spare Name',
                'Spare Type',
                'Asset Type'
            ];
            
            $cell_values = [
                $key + 1,
                $spare['spare_code'], 
                $spare['spare_name'],
                $spare->SpareType['spare_type_name'],
                optional($spare->SpareAssetTypes)->map(function($assetType) {
                    return $assetType->AssetType['asset_type_name']; 
                })->implode(', ')
            ];

            foreach ($spareValues as $attributeValue) 
            {
                array_push($headers, $attributeValue['SpareAttributeData']['display_name']); 
                array_push($cell_values, $attributeValue['field_value']); 
            }

            array_push($rows, $headers);
            array_push($rows, $cell_values);
        }

        return view('exports.Spare', [
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
