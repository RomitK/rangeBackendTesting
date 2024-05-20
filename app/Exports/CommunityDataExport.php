<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;

class CommunityDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{

    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Display On Home Page',
            'Projects Count',
            'Properties Count',
            'Status Name',
            'Approval Status',
            'Approval By',
            'Added By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($community): array
    {
        return [
            $community->id,
            $community->name,
            $community->is_display_home,
            $community->projects->count(),
            countPropertiesForCommunity($community->id),
            $community->status,
            $community->is_approved,
            $community->approval ? $community->approval->name : '',
            $community->user->name,
            $community->formattedCreatedAt,
            $community->formattedUpdatedAt,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
}
