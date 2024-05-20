<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;

class ProjectDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'Completion Status',
            'HandOver',
            'Property Type',
            'Display On Home Page',
            'Community',
            'Developer Name',
            'Unit Types',
            'Payment',
            'Properties',
            'Status Name',
            'Approval Status',
            'Approval By',
            'Added By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($project): array
    {
        return [
            $project->id,
            $project->title,
            $project->reference_number,
            $project->permit_number,
            $project->completionStatus ? $project->completionStatus->name : '',
            $project->completion_date,
            $project->accommodations ? $project->accommodations->name : '',
            $project->is_display_home,
            $project->mainCommunity ? $project->mainCommunity->name : '',
            $project->developer ? $project->developer->name : '',
            $project->subProjects->count(),
            $project->mPaymentPlans->count(),
            $project->properties->count(),
            $project->status,
            $project->is_approved,
            $project->approval ? $project->approval->name : '',
            $project->user->name,
            $project->formattedCreatedAt,
            $project->formattedUpdatedAt,
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
