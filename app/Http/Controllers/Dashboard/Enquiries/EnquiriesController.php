<?php

namespace App\Http\Controllers\Dashboard\Enquiries;

use App\DataTables\EnquiriesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EnquiriesController extends Controller
{
    public function index(EnquiriesDataTable $dataTable): JsonResponse|View
    {
        return $dataTable->render('dashboard.enquiries.index', compact('dataTable'));
    }
}
