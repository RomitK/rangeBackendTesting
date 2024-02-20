<?php

namespace App\Http\Controllers\Dashboard;

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
use Auth;
use DB;

class FaqController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.page_contents'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', ]
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($page)
    {
        return view('dashboard.pageContents.faqs.create', compact('page'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $faq = new Faq;
            $faq->page_name = $request->page_name;
            $faq->question = $request->question;
            $faq->orderBy = $request->orderBy;
            $faq->is_contact = $request->is_contact;
            $faq->status = $request->status;
            $faq->answer = $request->answer;
            $faq->long_answer = $request->long_answer;
            $faq->user_id = Auth::user()->id;
            $faq->save();

            return redirect()->route(config('constants.'.$request->page_name.'.route'))->with('success','FAQ has been created successfully.');

        }catch(\Exception $error){
            return redirect()->route(config('constants.'.$request->page_name.'.route'))->with('error',$error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($page, Faq $faq)
    {
        return view('dashboard.pageContents.faqs.edit', compact('page', 'faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $page, Faq $faq)
    {
        try{
            $faq->page_name = $request->page_name;
            $faq->question = $request->question;
            $faq->orderBy = $request->orderBy;
            $faq->answer = $request->answer;
            $faq->is_contact = $request->is_contact;
            $faq->status = $request->status;
            $faq->long_answer = $request->long_answer;
            $faq->save();

            return redirect()->route(config('constants.'.$request->page_name.'.route'))->with('success','FAQ has been created successfully.');

        }catch(\Exception $error){
            return redirect()->route(config('constants.'.$request->page_name.'.route'))->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($page, Faq $faq)
    {
        try{
            $faq->delete();
            return redirect()->route(config('constants.'.$page.'.route'))->with('success','FAQ has been deleted successfully.');

        }catch(\Exception $error){
            return redirect()->route(config('constants.'.$page.'.route'))->with('error',$error->getMessage());
        }
    }
}
