<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class RefractoryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data->map(function ($item, $key) 
        {
            $firstUserService = $item->userService->first();
            return [
                'Sl No' => $key + 1,
                'Asset' => $firstUserService->Asset->asset_name ?? '',
                'Asset Type' => $firstUserService->Asset->AssetType->asset_type_name ?? '',
                'Spare Type' => optional($item->Spare->SpareType)->spare_type_name ?? '',
                'Spare' => optional($item->Spare)->spare_name ?? '',
                'Total Quantity' => $item->quantity,
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'SL No',
            'Asset Name',
            'Asset Type',
            'Spare Type',
            'Spare',
            'Total Quantity',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 25,
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 15
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0073e6'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}
