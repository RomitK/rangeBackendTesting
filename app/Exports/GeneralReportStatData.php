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
use Maatwebsite\Excel\Concerns\WithTitle;

class GeneralReportStatData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $statusRowIndex;
    protected $approvalStatusRowIndex;
    protected $dateCountRowIndex;
    protected $projectPermitNameRowIndex;
    protected $propertyPermitNameRowIndex;
    protected $propertyCategoryNameRowIndex;
    protected $propertyAgentNameRowIndex;
    protected $MediaRowIndex;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $collection = collect();

        // Adding counts by status
        $this->statusRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Data', 'Available', 'NA', 'Rejected', 'Requested', 'Total Active', 'Total Inactive', 'Total']);

        foreach ($this->data['getCountsByStatus'] as $index => $getCountsByStatus) {

            $available = $getCountsByStatus->available ?? 0;
            $NA = $getCountsByStatus->NA ?? 0;
            $rejected = $getCountsByStatus->rejected ?? 0;
            $requested = $getCountsByStatus->requested ?? 0;
            $total = $available + $NA + $rejected + $requested;
            $totalActive = $available + $NA;
            $totalInactive = $rejected + $requested;

            $indexCapitalized = ucfirst($index);

            $collection->push([
                $indexCapitalized,
                (string) $available,
                (string) $NA,
                (string) $rejected,
                (string) $requested,
                (string) $totalActive,
                (string) $totalInactive,
                (string) $total
            ]);
        }


        // Adding counts by date
        $this->dateCountRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Date', 'Communities', 'Developers', 'Projects', 'Properties', 'Media', 'Guides', 'Careers', 'Total']);

        $allDates = array_unique(array_merge(
            array_keys($this->data['getCountsByDate']['communities']),
            array_keys($this->data['getCountsByDate']['developers']),
            array_keys($this->data['getCountsByDate']['projects']),
            array_keys($this->data['getCountsByDate']['properties']),
            array_keys($this->data['getCountsByDate']['medias']),
            array_keys($this->data['getCountsByDate']['guides']),
            array_keys($this->data['getCountsByDate']['careers'])
        ));

        $totalCommunities = 0;
        $totalDevelopers = 0;
        $totalProjects = 0;
        $totalProperties = 0;
        $totalMedia = 0;
        $totalGuide = 0;
        $totalAgent = 0;

        foreach ($allDates as $date) {
            $communityCount = $this->data['getCountsByDate']['communities'][$date] ?? 0;
            $developerCount = $this->data['getCountsByDate']['developers'][$date] ?? 0;
            $projectCount = $this->data['getCountsByDate']['projects'][$date] ?? 0;
            $propertyCount = $this->data['getCountsByDate']['properties'][$date] ?? 0;
            $mediaCount = $this->data['getCountsByDate']['medias'][$date] ?? 0;
            $guideCount = $this->data['getCountsByDate']['guides'][$date] ?? 0;
            $agentCount = $this->data['getCountsByDate']['careers'][$date] ?? 0;

            $totalCount = $communityCount + $developerCount + $projectCount + $propertyCount + $mediaCount + $guideCount + $agentCount;

            $collection->push([
                $date,
                (string) $communityCount,
                (string) $developerCount,
                (string) $projectCount,
                (string) $propertyCount,
                (string) $mediaCount,
                (string) $guideCount,
                (string) $agentCount,
                (string) $totalCount
            ]);

            $totalCommunities += $communityCount;
            $totalDevelopers += $developerCount;
            $totalProjects += $projectCount;
            $totalProperties += $propertyCount;
            $totalMedia += $mediaCount;
            $totalGuide += $guideCount;
            $totalAgent += $agentCount;
        }

        // Add the grand total row
        $collection->push([
            'Total',
            (string) $totalCommunities,
            (string) $totalDevelopers,
            (string) $totalProjects,
            (string) $totalProperties,
            (string) $totalMedia,
            (string) $totalGuide,
            (string) $totalAgent,
            (string) ($totalCommunities + $totalDevelopers + $totalProjects + $totalProperties + $totalMedia + $totalGuide + $totalAgent)
        ]);

        $totalProjectPermitCount = 0;
        $this->projectPermitNameRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Project Permit Status', 'Count']);

        foreach ($this->data['projectPermitCounts'] as $data) {
            $count = (int)$data['count']; // Ensure the count is an integer
            $collection->push([$data['status'], (string)$count]);
            $totalProjectPermitCount += $count; // Sum the counts
        }

        $collection->push(['Total', $totalProjectPermitCount]);


        $totalPropertyPermitCount = 0;
        $this->propertyPermitNameRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Property Permit Status', 'Count']);

        foreach ($this->data['propertyPermitCounts'] as $data) {
            $count = (int)$data['count']; // Ensure the count is an integer
            $collection->push([$data['status'], (string)$count]);
            $totalPropertyPermitCount += $count; // Sum the counts
        }
        $collection->push(['Total', $totalPropertyPermitCount]);

        $totalPropertyCategoryCount = 0;
        $this->propertyCategoryNameRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Property Categories', 'Count']);

        foreach ($this->data['propertyCateoryCounts'] as $data) {
            $count = (int)$data['count']; // Ensure the count is an integer
            $collection->push([$data['status'], (string)$count]);
            $totalPropertyCategoryCount += $count; // Sum the counts
        }
        $collection->push(['Total', $totalPropertyCategoryCount]);


        $this->propertyAgentNameRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Agent Name', 'Ready', 'Offplan', 'Rent', 'Total']);

        foreach ($this->data['propertyAgentWiseCounts'] as $data) {

            $ready = $data['ready'];
            $offplan = $data['offplan'];
            $rent = $data['rent'];
            $total = (int)$ready + (int)$offplan + (int)$rent; // Ensure the count is an integer

            $collection->push([$data['agent_name'], (string)$ready, (string)$offplan, (string)$rent, $total]);
        }




        $totalMediaCount = 0;
        $this->MediaRowIndex = $collection->count() + 1; // 1-based index for Excel rows
        $collection->push(['Media Type', 'Count']);

        foreach ($this->data['blogCategoryCounts'] as $data) {
            $count = (int)$data['count']; // Ensure the count is an integer
            $collection->push([$data['status'], (string)$count]);
            $totalMediaCount += $count; // Sum the counts
        }
        $collection->push(['Total', $totalMediaCount]);


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
    public function title(): string
    {
        return 'General Report Stat Data'; // Set the name for this subsheet
    }
    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        $sheet->getStyle('A' . ($this->statusRowIndex + 1) . ':H' . ($this->statusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);


        $sheet->getStyle('A' . ($this->approvalStatusRowIndex + 1) . ':E' . ($this->approvalStatusRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);



        $sheet->getStyle('A' . ($this->projectPermitNameRowIndex + 1) . ':B' . ($this->projectPermitNameRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);


        $sheet->getStyle('A' . ($this->propertyPermitNameRowIndex + 1) . ':B' . ($this->propertyPermitNameRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

        $sheet->getStyle('A' . ($this->propertyCategoryNameRowIndex + 1) . ':B' . ($this->propertyCategoryNameRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

        $sheet->getStyle('A' . ($this->propertyAgentNameRowIndex + 1) . ':E' . ($this->propertyAgentNameRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);


        $sheet->getStyle('A' . ($this->MediaRowIndex + 1) . ':E' . ($this->MediaRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);

        $sheet->getStyle('A' . ($this->dateCountRowIndex + 1) . ':I' . ($this->dateCountRowIndex + 1))->applyFromArray([
            'font' => ['bold' => true],
        ]);
    }
}
