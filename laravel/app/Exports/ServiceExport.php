<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class ServiceExport implements FromView, WithColumnWidths
{
    // protected $services;

    public function __construct()
    {
       
    }

    // public function view(): View
    // {
    //     $services = Service::all(); 
    //     $headers = [];
    //     $values = [];

    //     foreach ($services as $key => $service) 
    //     {
    //         $serviceValues = $service->getServiceValues($service->service_id);
    //         $rows = [];
    //         $cell_values = [];
    //         if (empty($headers)) {
    //             $header = ([
    //                 '#',
    //                 'Service Code',
    //                 'Service Name',
    //                 'Service Type',
    //                 'Asset Type'
    //             ]);
                
    //             $cell_values = ([
    //                 $key+1,
    //                 $service['service_code'],
    //                 $service['service_name'],
    //                 $service->ServiceType['service_type_name'],
    //                 // $service->serviceAssetTypes->AssetType['asset_type_name']
    //                 "data"
    //             ]);
    //             foreach ($serviceValues as $attributeValue) 
    //             {
    //                 array_push($header, $attributeValue['ServiceAttributeData']['display_name']);
    //                 array_push($cell_values, $attributeValue['field_value']);
    //             }
    //             array_push($rows, $header);
    //             array_push($rows, $cell_values);
    //         }

    //     }

    //     return view('exports.Service', [
    //         'rows' => $rows
    //     ]);
    // }

    public function view(): View
    {
        $services = Service::all(); 
        $rows = []; 

        foreach ($services as $key => $service) 
        {
            $serviceValues = $service->getServiceValues($service->service_id);

            $headers = [
                'Sl No',
                'Service Code',
                'Service Name',
                'Service Type',
                'Asset Type'
            ];
            
            $cell_values = [
                $key + 1,
                $service['service_code'],
                $service['service_name'],
                $service->ServiceType['service_type_name'],
                $service->serviceAssetTypes->map(function($assetType) {
                    return $assetType->AssetType['asset_type_name'];
                })->implode(', ') 
            ];

            foreach ($serviceValues as $attributeValue) {

                array_push($headers, $attributeValue['ServiceAttributeData']['display_name']);
            
                array_push($cell_values, $attributeValue['field_value']);
            }

    
            array_push($rows, $headers);
            array_push($rows, $cell_values);
        }


        return view('exports.Service', [
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