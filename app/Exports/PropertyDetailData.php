<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class PropertyDetailData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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
                $item['name'],
                $item['reference_number'],
                isset($item['project']) ? $item['project']['permit_number'] : '',
                isset($item['project']) ? $item['project']['title'] : '',
                isset($item['subProject']) ? $item['subProject']['title'] : '',
                isset($item['subProject']) ? $item['subProject']['list_type'] : '',
                isset($item['project']) ? $item['project']['mainCommunity'] ? $item['project']['mainCommunity']['name'] : '' : '',
                isset($item['project']) ? $item['project']['developer'] ? $item['project']['developer']['name'] : '' : "",

                $item['price'],
                $item['exclusive'],
                isset($item['agent']) ? $item['agent']['name'] : '',
                isset($item['category']) ? $item['category']['name'] : '',
                isset($item['completionStatus']) ? $item['completionStatus']['name'] : '',
                isset($item['accommodations']) ? $item['accommodations']['name'] : '',
                $item['status'],
                $item['is_approved'],
                $approvalName,
                $item->out_of_inventory,
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
            'Status Name',
            'Approval Status',
            'Available in Inventory',
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
        return 'Properties Data'; // Set the name for this subsheet
    }
}
