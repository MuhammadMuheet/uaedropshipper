<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AreasExport implements FromArray, WithHeadings
{
    protected $areas;

    public function __construct(array $areas)
    {
        $this->areas = $areas;
    }

    public function array(): array
    {
        return $this->areas;
    }

    public function headings(): array
    {
        return [
            'State Name',
            'Area',
            'Shipping Cost',
        ];
    }
}