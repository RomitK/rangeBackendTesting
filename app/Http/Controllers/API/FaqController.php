<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{
    Banner,
    Faq,
    Counter,
    Partner,
    PageContent
};
use App\Http\Resources\{
    FaqListResource
};
use Auth;
use DB;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            
            $collection = Faq::query();
            if(isset($request->keyword)){
                $question = $request->keyword;
                $collection->where('question','like', "%$question%");
            }
            $faqs =  $collection->OrderBy('OrderBy', 'asc')->active()->get();
            $faqs = FaqListResource::collection($faqs);
            return $this->success('FAQ Data', $faqs, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    
    public function contactFaqs()
    {
        try{
           $faqs =  Faq::OrderBy('OrderBy', 'asc')->active()->where('is_contact', 1)->get();
           $faqs = FaqListResource::collection($faqs);
            return $this->success('FAQ Data', $faqs, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
