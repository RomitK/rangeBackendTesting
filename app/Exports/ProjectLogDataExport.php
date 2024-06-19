<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProjectLogDataExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $approvalStatusRowIndex;
    protected $communityNameRowIndex;
    protected $statusRowIndex;
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Initialize the collection with startDate and endDate in the first row
        $collection = collect();

        $userName = isset($this->data['user']) ? $this->data['user']['name'] : '';

        // Use ternary operator to conditionally set approval's name
        $approvalName = isset($this->data['approval']) ?  $this->data['approval']['name'] : '';

        $completionStatus = isset($this->data['completionStatus']) ?  $this->data['completionStatus']['name'] : '';

        $accommodations = isset($this->data['accommodations']) ?  $this->data['accommodations']['name'] : '';

        $mainCommunity = isset($this->data['mainCommunity']) ?  $this->data['mainCommunity']['name'] : '';

        $developer = isset($this->data['developer']) ?  $this->data['developer']['name'] : '';

        $collection->push([

            $this->data['title'],
            $this->data['reference_number'],
            $this->data['permit_number'],
            $this->data['qr_link'],
            $completionStatus,
            $this->data['completion_date'],
            $accommodations,
            $this->data['is_display_home'],
            $mainCommunity,
            $developer,
            $this->data['subProjects']->count(),
            $this->data['mPaymentPlans']->count(),
            $this->data['properties']->count(),
            ucfirst($this->data['website_status']),
            // $this->data['status,
            // $this->data['is_approved,
            $approvalName,
            $userName,
            $this->data['formattedCreatedAt'],
            $this->data['formattedUpdatedAt'],
        ]);


        $this->statusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['SR NO', 'Date', 'User Name', 'Website Status', "Message", 'Action']);

        foreach ($this->data['logActivity'] as $key => $data) {

            $properties = json_decode($data['properties'], true);

            $website_status = isset($properties['new']) ? $properties['new']['website_status'] : null;
            $attribute = isset($properties['attribute']) ? $properties['attribute'] : null;
            $description = isset($data['description']) ? $data['description'] : null;

            $collection->push([$key + 1, $data['formattedCreatedAt'],  $data['user']['name'],  ucfirst($website_status), $description, $attribute]);
        }

        return $collection;
    }

    // Define headings for columns
    public function headings(): array
    {
        return [
            'Name',
            'Reference Number',
            'Permit Number',
            'QR Code Link',
            'Completion Status',
            'HandOver',
            'Property Type',
            'Display On Home Page',
            'Community',
            'Developer Name',
            'Unit Types',
            'Payment',
            'Properties',
            'Website Status',
            // 'Status Name',
            // 'Approval Status',
            'Approval By',
            'Added By',
            'Created At',
            'Updated At',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Apply bold font to the headings
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Apply bold font to the 'Approval Status' row
        $sheet->getStyle('A' . ($this->statusRowIndex + 1) . ':E' . ($this->statusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);
    }
}
