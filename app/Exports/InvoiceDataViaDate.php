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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InvoiceDataViaDate implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $statusRowIndex;
    protected $approvalStatusRowIndex;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collection = collect();

        $this->statusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Data', 'Active', 'Inactive', 'Total']);

        foreach ($this->data['getCountsByStatus'] as $index => $getCountsByStatus) {
            $inactive = $getCountsByStatus->inactive ?? 0;
            $total = $getCountsByStatus->active + $inactive;
            $indexCapitalized = ucfirst($index);
            $collection->push([$indexCapitalized, $getCountsByStatus->active, $inactive, $total]);
        }


        $this->approvalStatusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Data', 'Requested', 'Rejected', 'Approved', 'Total']);

        foreach ($this->data['getCountsByApproval'] as $index => $getCountsByApproval) {
            $requested = $getCountsByApproval->requested ?? 0;
            $rejected = $getCountsByApproval->rejected ?? 0;
            $approved = $getCountsByApproval->approved ?? 0;
            $total = $requested + $rejected + $approved;
            $indexCapitalized = ucfirst($index);
            $collection->push([$indexCapitalized, $requested, $rejected, $approved, $total]);
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
       
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        $sheet->getStyle('A' . ($this->statusRowIndex + 1) . ':D' . ($this->statusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

      
        $sheet->getStyle('A' . ($this->approvalStatusRowIndex + 1) . ':E' . ($this->approvalStatusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

    }
}
