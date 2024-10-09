<?php

namespace App\Exports;

use App\Models\ActivityAttribute;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class ActivityAttributeExport implements FromView, WithColumnWidths
{
    private $activityAttributes;

    public function __construct()
    {
        $this->activityAttributes = ActivityAttribute::all(); 
    }

    public function view(): View
    {
        return view('exports.ActivityAttribute', [
            'activity_attributes' => $this->activityAttributes 
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