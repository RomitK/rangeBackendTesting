<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class cronJobMainController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.cronJobs'),
        ['only' => ['index']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.cronManagement.cronJobs.index');
    }
}
