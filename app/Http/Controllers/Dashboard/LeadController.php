<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\LeadRequest;
use App\Models\Lead;
use Auth;

class LeadController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.leads'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']
        ]);
    }
    public function moveToCRM(Request $request, Lead $lead)
    {
        try{
           
            $postData = [
                'name'  =>  $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'form_name' => $lead->form_name,
                'message' => $lead->message,
                'page_url' => $lead->page_url,
                'booking_date' => $lead->booking_date,
                'booking_time' => $lead->booking_time,
                'submit_date'=> $lead->created_at
            ];
            
            $url = 'https://hooks.zapier.com/hooks/catch/3658724/3ax5jzt/';
            $headers_curl  = [ 'Content-Type: application/json'];
    					            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));           
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_curl);
            $result = curl_exec($ch);
            curl_close($ch);
        
            $response = json_decode($result);
            if($response->status=='success'){
                $lead->is_crm = 1;
                $lead->save();
               return view('dashboard.leadManagement.leads.show',compact('lead'))->with('success',"Lead has been moved to CRM");
            }
        }catch(\Exception $error){
            return redirect()->route('dashboard.leads.index')->with('error',$error->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::orderBy('id','desc')->get();

        return view('dashboard.leadManagement.leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.leadManagement.leads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadRequest $request)
    {
        try{
            $lead = new Lead;
            $lead->name = $request->name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->form_name = $request->form_name;
            $lead->detail = $request->detail;
            $lead->message = $request->message;
            $lead->status = $request->status;
            $lead->page_url = $request->page_url;
            if ($request->hasFile('attachment')) {
                $property->addMediaFromRequest('attachment')->toMediaCollection('attachments');
            }
            $lead->save();
            return redirect()->route('dashboard.leads.index')->with('success','Leads has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.leads.index')->with('error',$error->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        return view('dashboard.leadManagement.leads.show',compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        return view('dashboard.leadManagement.leads.edit',compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeadRequest $request, Lead $lead)
    {
        try{
            $lead->name = $request->name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->form_name = $request->form_name;
            $lead->detail = $request->detail;
            $lead->message = $request->message;
            $lead->status = $request->status;
            $lead->page_url = $request->page_url;
            if ($request->hasFile('attachment')) {
                $lead->clearMediaCollection('attachments');
                $lead->addMediaFromRequest('attachment')->toMediaCollection('attachments');
            }
            $lead->save();
            return redirect()->route('dashboard.leads.index')->with('success','Leads has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.leads.index')->with('error',$error->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Lead::find($id)->delete();

            return redirect()->route('dashboard.leads.index')->with('success','Lead has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.leads.index')->with('error',$error->getMessage());
        }

    }
}
