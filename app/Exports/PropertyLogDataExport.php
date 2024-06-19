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

class PropertyLogDataExport  implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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

        $category = isset($this->data['category']) ?  $this->data['category']['name'] : '';

        $agent = isset($this->data['agent']) ?  $this->data['agent']['name'] : '';
        $project = isset($this->data['project']) ?  $this->data['project']['title'] : '';
        $permit_number = isset($this->data['project']) ?  $this->data['project']['permit_number'] : '';
        $subProject = isset($this->data['subProject']) ?  $this->data['subProject']['title'] : '';
        $list_type = isset($this->data['list_type']) ?  $this->data['subProject']['list_type'] : '';
        $mainCommunity = isset($this->data['project']) ?  $this->data['project']['mainCommunity']['name'] : '';
        $developer = isset($this->data['project']) ?  $this->data['project']['developer']['name'] : '';

        $collection->push([


            $this->data['name'],
            $this->data['reference_number'],
            $permit_number,
            $project,
            $subProject,
            $list_type,
            $mainCommunity,
            $developer,
            $this->data['price'],
            $this->data['exclusive'],
            $agent,
            $category,
            $completionStatus,
            $accommodations,

            ucfirst($this->data['website_status']),

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
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Apply bold font to the headings
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Apply bold font to the 'Approval Status' row
        $sheet->getStyle('A' . ($this->statusRowIndex + 1) . ':E' . ($this->statusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);
    }
}
