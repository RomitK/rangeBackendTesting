<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\RevalidateEverythingAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RevalidateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, RevalidateEverythingAction $revalidateEverythingAction)
    {
        $revalidateEverythingAction->execute();

        return response()->json([
            'success' => true
        ]);
    }
}
