<?php

namespace App\Exports;

use App\Models\AssetAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class AssetAttributeExport implements FromView, WithColumnWidths
{
    private $assetAttributes;

    public function __construct()
    {
        $this->assetAttributes = AssetAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.AssetAttribute', [
            'asset_attributes' => $this->assetAttributes 
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 35,
            'C' => 14,
            'D' => 14,
            'E' => 12,
            'F' => 14,
            'G' => 12,
            'H' => 12
        ];
    }
}