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

class PropertyDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'Reference Number',
            'Permit Number',
            'Project',
            'Unit',
            'Market',
            'Community',
            'Developer',
            'Price',
            'Exclusive',
            'Agent',
            'Category',
            'Completion Status',
            'Property Type',
            'Website Status',
            // 'Status Name',
            // 'Approval Status',
            'Approval By',
            'Added By',
            'Created At',
            'Updated At',
            'QR',
        ];
    }

    public function map($property): array
    {
        return [
            $property->id,
            $property->name,
            $property->reference_number,
            $property->project ? $property->project->permit_number ?  $property->project->permit_number : '' : '',
            $property->project ? $property->project->title : '',
            $property->subProject ? $property->subProject->title : '',
            $property->subProject ? $property->subProject->list_type : '',
            $property->project ? $property->project->mainCommunity ? $property->project->mainCommunity->name : '' : '',
            $property->project ? $property->project->developer ? $property->project->developer->name : '' : '',
            $property->price,
            $property->exclusive,
            $property->agent ? $property->agent->name : '',
            $property->category ? $property->category->name : '',
            $property->completionStatus ? $property->completionStatus->name : '',
            $property->accommodations ? $property->accommodations->name : '',
            //$property->websiteStatus,
            ucfirst($property->website_status),
            // $property->status,
            // $property->is_approved,
            $property->approval ? $property->approval->name : '',
            $property->user->name,
            $property->formattedCreatedAt,
            $property->formattedUpdatedAt,
            $property->project ? $property->project->qr_link ?  $property->project->qr_link : '' : '',
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
