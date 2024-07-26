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

class AgentDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'Employer Id',
            'Name',
            'Email',
            'Contact Number',
            'Whatsapp Number',
'Department',
            'Designation',
            'Status',
            'Added By',
            'Created At',
            'Marketing Profile Link',
            'QR Link',
        ];
    }

    public function map($agent): array
    {
        return [
            $agent->id,
            $agent->employeeId,
            $agent->name,
            $agent->email,
            $agent->contact_number,
            $agent->whatsapp_number,
$agent->department,
            $agent->designation,
            $agent->status,
            $agent->user->name,
            $agent->formattedCreatedAt,
            config('app.frontend_url') . 'profile/' . $agent->profileUrl . '/' . $agent->slug,
            $agent->qr

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
