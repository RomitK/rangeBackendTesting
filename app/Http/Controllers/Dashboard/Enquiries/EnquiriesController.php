<?php

namespace App\Http\Controllers\Dashboard\Enquiries;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\View\View;

class EnquiriesController extends Controller
{
    public function index(): View
    {
        $enquiries = Enquiry::query()->cursor();

        return view('dashboard.enquiries.index', compact('enquiries'));
    }
}
