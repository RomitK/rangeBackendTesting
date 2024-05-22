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

class DeveloperReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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

        $this->statusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Status', 'Count']);
        // Add statusWiseData
        foreach ($this->data['statusWiseData'] as $data) {
            $collection->push([$data['status'], $data['count']]);
        }

        $this->approvalStatusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        // Add Approval Status
        $collection->push(['Approval Status', 'Count']);


        // Add approvalWiseData
        foreach ($this->data['approvalWiseData'] as $data) {
            $collection->push([$data['status'], $data['count']]);
        }
        $this->communityNameRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        // Add Community Name, Projects Count, Properties Count
        $collection->push(['Developer Name', 'Projects Count', 'Properties Count']);

        // Add projectPropertiseWiseData
        foreach ($this->data['projectPropertiseWiseData'] as $projectData) {
            $collection->push([$projectData['name'], $projectData['projects'], $projectData['properties']]);
        }

        return $collection;
    }

    // Define headings for columns
    public function headings(): array
    {
        return [
            'Start Date',
            $this->data['startDate'],
            'End Date',
            $this->data['endDate']
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Apply bold font to the headings
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Apply bold font to the 'Approval Status' row
        $sheet->getStyle('A' . ($this->statusRowIndex + 1) . ':B' . ($this->statusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);
        // Apply bold font to the 'Approval Status' row
        $sheet->getStyle('A' . ($this->approvalStatusRowIndex + 1) . ':B' . ($this->approvalStatusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Apply bold font to the 'Community Name' row
        $sheet->getStyle('A' . ($this->communityNameRowIndex + 1) . ':C' . ($this->communityNameRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);
    }
}
