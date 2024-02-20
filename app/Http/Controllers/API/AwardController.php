<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AwardRequest;
use Illuminate\Support\Str;
use App\Models\{
    Award,
    Developer
};
use App\Http\Resources\{
    AwardResource
};

use Auth;
use DB;
class AwardController extends Controller
{
    public function index()
    {
        
        try{
            $awards = AwardResource::collection(Award::active()->orderByRaw('ISNULL(awardOrder)')->orderBy('awardOrder', 'asc')->get());
            
            return $this->success('awards', $awards, 200);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
}
