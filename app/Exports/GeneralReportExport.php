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
use App\Exports\{
    InvoiceDataViaDate,
    GeneralReportStatData,
    CommunityDetailData,
    DeveloperDetailData,
    ProjectDetailData,
    PropertyDetailData,
    BlogDetailData,
    GuideDetailData,
    AgentDetailData
};

class GeneralReportExport implements WithMultipleSheets
{
    protected $data;
    protected $statusRowIndex;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [
            new GeneralReportStatData($this->data),
            // new CommunityDetailData($this->data['communities']),
            // new DeveloperDetailData($this->data['developers']),
            // new ProjectDetailData($this->data['projects']),
            // new PropertyDetailData($this->data['properties']),
            // new BlogDetailData($this->data['blogs']),
            // new GuideDetailData($this->data['guides']),
            // new CareerDetailData($this->data['careers'])
        ];

        return $sheets;
    }
}
