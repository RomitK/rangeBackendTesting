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

class DeveloperDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'Projects Count',
            'Properties Count',
            'Website Status',
            // 'Status Name',
            // 'Approval Status',
            'Approval By',
            'Added By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($developer): array
    {
        return [
            $developer->id,
            $developer->name,
            $developer->projects->count(),
            countPropertiesForDeveloper($developer->id),
            ucfirst($developer->website_status),
            // $developer->status,
            // $developer->is_approved,
            $developer->approval ? $developer->approval->name : '',
            $developer->user->name,
            $developer->formattedCreatedAt,
            $developer->formattedUpdatedAt,
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
