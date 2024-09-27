<?php

namespace App\Exports;

use App\Models\DataSource;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class DataSourceExport implements FromView, WithColumnWidths
{
    //private $data_sources;

    public function __construct()
    {
    }

    public function view(): View
    {
        $data_sources = DataSource::all(); 
        $rows = []; 

        foreach ($data_sources as $key => $data_source) 
        {
            $dataSourceValues = $data_source->getDataSourceValues($data_source->data_source_id);

            $headers = [
                'Sl No',
                'DataSource Code',
                'DataSource Name',
                'DataSource Type',
                'Asset Type'
            ];
            
            $cell_values = [
                $key + 1,
                $data_source['data_source_code'], 
                $data_source['data_source_name'],
                $data_source->DataSourceType['data_source_type_name'],
                optional($data_source->DataSourceAssetTypes)->map(function($assetType) {
                    return $assetType->AssetType['asset_type_name']; 
                })->implode(', ')
            ];

            foreach ($dataSourceValues as $attributeValue) 
            {
                array_push($headers, $attributeValue['DataSourceAttributeValue']['display_name']); 
                array_push($cell_values, $attributeValue['field_value']); 
            }

            array_push($rows, $headers);
            array_push($rows, $cell_values);
        }

        return view('exports.DataSource', [
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