<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;


class DataExport implements FromCollection
{
    public function collection()
    {
        $projects = Project::active()->latest()->get();
        Log::info('DataExport');
        Log::info($projects);

        return $projects;
    }
}
