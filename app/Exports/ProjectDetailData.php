<?php

namespace App\Exports;


use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProjectDetailData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            // Use ternary operator to conditionally set user's name
            $userName = isset($item['user']) ? $item['user']['name'] : '';

            // Use ternary operator to conditionally set approval's name
            $approvalName = isset($item['approval']) ? $item['approval']['name'] : '';



            return [
                $item['id'],
                $item['title'],
                $item['reference_number'],
                $item['permit_number'],
                isset($item['completionStatus']) ? $item['completionStatus']['name'] : '',
                $item['completion_date'],
                isset($item['accommodation']) ? $item['accommodation']['name'] : '',
                $item['is_display_home'],
                isset($item['mainCommunity']) ? $item['mainCommunity']['name'] : '',
                isset($item['developer']) ? $item['developer']['name'] : '',
                $item['subProjects']->count(),
                $item['mPaymentPlans']->count(),
                $item['properties']->count(),
                $item['status'],
                $item['is_approved'],
                $approvalName,
                $userName,
                $item['formattedCreatedAt'],
                $item['formattedUpdatedAt'],
            ];
        });
    }

    // Define headings for columns
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

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function title(): string
    {
        return 'Projects Data'; // Set the name for this subsheet
    }
}
