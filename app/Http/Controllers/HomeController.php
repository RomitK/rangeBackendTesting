<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Article,
    User,
    Property,
    Testimonial,
    Developer,
    Career,
    Project,
    Agent,
};
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $activeUserCount = User::latest()->where('role','!=', config('constants.super_admin'))->count();
        $activePropertyCount = Property::latest()->count();
        $activeTestimonialCount = Testimonial::latest()->count();
        $activeBlogCount = Article::latest()->count();
        $activeDeveloperCount = Developer::latest()->count();
        $activeCareerCount = Career::latest()->count();
        $activeProjectCount = Project::latest()->mainProject()->count();
        $activeTeamCount = Agent::latest()->count();
        $activeJobCount =Career::latest()->count();

        return view('dashboard.dashboard', compact([
            'activeUserCount',
            'activePropertyCount',
            'activeBlogCount',
            'activeTestimonialCount',
            'activeProjectCount',
            'activeCareerCount',
            'activeDeveloperCount',
            'activeTeamCount',
            'activeJobCount',
        ]));
    }
}
