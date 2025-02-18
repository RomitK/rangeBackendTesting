<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AgentRequest;
use Illuminate\Support\Str;
use App\Models\{
    Agent,
    Language,
    Service,
    Developer,
    Community,
    Project
};
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Jobs\AgentExportAndEmailData;


class AgentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:' . config('constants.Permissions.teams'), ['only' => ['index', 'create', 'edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $page_size = 25;
        $current_page = isset($request->item) ? $request->item : $page_size;
        if (isset($request->page)) {
            $sr_no_start = ($request->page * $current_page) - $current_page + 1;
        } else {
            $sr_no_start = 1;
        }

        $collection = Agent::with('user');

        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }

	if (isset($request->department)) {
            $collection->where('department', $request->department);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }

        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $properties = $collection->orderBy($orderBy, $direction);


            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                AgentExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $agents = $collection->paginate($current_page);
            }
        } else {
            $collection = $collection->latest();
            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                AgentExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {

                $agents = $collection->paginate($current_page);
            }
        }


        return view('dashboard.realEstate.agents.index', compact(
            'agents',
            'sr_no_start',
            'current_page'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::active()->latest()->get();
        $services = Service::mainService()->active()->latest()->get();
        $developers = Developer::active()->latest()->get();
        $communities = Community::active()->latest()->get();
        $projects = Project::mainProject()->active()->latest()->get();

        return view('dashboard.realEstate.agents.create', compact('projects', 'communities', 'developers', 'languages', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgentRequest $request)
    {
        try {
            $agent = new Agent;
            $agent->name = $request->name;
            $agent->status = $request->status;
            $agent->email = $request->email;
            $agent->is_display_home = $request->is_display_home;
            $agent->orderBy = $request->orderBy;
            $agent->contact_number = $request->contact_number;
            $agent->whatsapp_number = $request->whatsapp_number;
            $agent->designation = $request->designation;
            $agent->specialization = $request->specialization;
            $agent->nationality = $request->nationality;
            $agent->experience = $request->experience;
            $agent->start_working = $request->start_working;
            $agent->linkedin_profile = $request->linkedin_profile;
            $agent->license_number = $request->license_number;
            $agent->message = $request->message;
            $agent->meta_title = $request->meta_title;
            $agent->is_management = $request->is_management;
            $agent->is_display_details = $request->is_display_details;
            $agent->meta_keywords = $request->meta_keywords;
            $agent->meta_description = $request->meta_description;
            $agent->employeeId = $request->employeeId;
            $agent->user_id = Auth::user()->id;
            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $imgExt;
                $agent->addMediaFromRequest('image')->usingFileName($imageName)->withResponsiveImages()->toMediaCollection('images', 'agentFiles');
            }
            if ($request->hasFile('additional_image')) {
                $img =  $request->file('additional_image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $imgExt;
                $agent->addMediaFromRequest('additional_image')->usingFileName($imageName)->withResponsiveImages()->toMediaCollection('additional_images', 'agentFiles');
            }


            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->title) . '.' . $ext;
                $agent->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'agentFiles');
            }

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $agent->is_approved = config('constants.approved');
                $agent->approval_id = Auth::user()->id;
            } else {
                $agent->is_approved = config('constants.requested');
            }
            $agent->updated_by = Auth::user()->id;
		    $agent->department = $request->department;
            $agent->save();

            $url = config('app.frontend_url') . 'profile/' . Str::slug($agent->profileUrl) . '/' . $agent->slug;
            $qrCode = QrCode::format('png')->size(200)->generate($url);

            $imageName = $agent->slug . '.png';
            Storage::disk('agentQRFiles')->put($imageName, $qrCode);
            $qrCodeUrl = Storage::disk('agentQRFiles')->url($imageName);
            $agent->clearMediaCollection('QRs');
            $agent->addMediaFromUrl($qrCodeUrl)->usingFileName($imageName)->toMediaCollection('QRs', 'agentFiles');

            $contact = [
                'version' => '3.0',
                'fn' =>$agent->name,
                'tel' => $agent->contact_number,
                'email' => $agent->email,
                'adr' => '1601, 16th Floor, Control Tower, Motor City, Dubai',
            ];

            $vcfContent = "BEGIN:VCARD\n";
            $vcfContent .= "VERSION:{$contact['version']}\n";
            $vcfContent .= "FN:{$contact['fn']}\n";
            $vcfContent .= "TEL:{$contact['tel']}\n";
            $vcfContent .= "EMAIL:{$contact['email']}\n";
            $vcfContent .= "ADR:{$contact['adr']}\n";
            $vcfContent .= "END:VCARD\n";

            $fileName = $agent->slug.'-contact.vcf';

            Storage::disk('agentCardFiles')->put($fileName, $vcfContent);


            $cardCodeUrl = Storage::disk('agentCardFiles')->url($fileName);
            
            $agent->addMediaFromUrl($cardCodeUrl)->usingFileName($fileName)->toMediaCollection('cards', 'agentFiles');

            $agent->save();

            if ($request->has('languageIds')) {
                $agent->languages()->attach($request->languageIds);
            }
            if ($request->has('serviceIds')) {
                $agent->services()->attach($request->serviceIds);
            }
            if ($request->has('communityIds')) {
                $agent->communities()->attach($request->communityIds);
            }
            if ($request->has('developerIds')) {
                $agent->developers()->attach($request->developerIds);
            }
            if ($request->has('projectIds')) {
                $agent->projects()->attach($request->projectIds);
            }
            return response()->json([
                'success' => true,
                'message' => 'Agent has been created successfully.',
                'redirect' => route('dashboard.agents.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.agents.index'),
            ]);
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
    public function edit(Agent $agent)
    {
        $languages = Language::active()->latest()->get();
        $services = Service::mainService()->active()->latest()->get();
        $developers = Developer::active()->latest()->get();
        $communities = Community::active()->latest()->get();
        $projects = Project::mainProject()->active()->latest()->get();
        return view('dashboard.realEstate.agents.edit', compact('projects', 'developers', 'communities', 'agent', 'languages', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgentRequest $request, Agent $agent)
    {
        try {
            $agent->name = $request->name;
            $agent->generateSlug();
            $agent->status = $request->status;
            $agent->employeeId = $request->employeeId;
            $agent->orderBy = $request->orderBy;
            $agent->is_display_home = $request->is_display_home;
            $agent->email = $request->email;
            $agent->contact_number = $request->contact_number;
            $agent->whatsapp_number = $request->whatsapp_number;
            $agent->is_management = $request->is_management;
            $agent->is_display_details = $request->is_display_details;
            $agent->designation = $request->designation;
            $agent->specialization = $request->specialization;
            $agent->nationality = $request->nationality;
            $agent->experience = $request->experience;
            $agent->start_working = $request->start_working;
            $agent->linkedin_profile = $request->linkedin_profile;
            $agent->license_number = $request->license_number;
            $agent->message = $request->message;
            $agent->meta_title = $request->meta_title;
            $agent->meta_keywords = $request->meta_keywords;
            $agent->meta_description = $request->meta_description;



            //$agent->user_id = Auth::user()->id;
            if ($request->hasFile('image')) {
                $agent->clearMediaCollection('images');
                $img =  $request->file('image');
                $imgExt = $img->getClientOriginalExtension();

                $imageName =  Str::slug($request->name) . '.' . $imgExt;
                $agent->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'agentFiles');
            }


            if ($request->hasFile('additional_image')) {
                $agent->clearMediaCollection('additional_images');
                $img =  $request->file('additional_image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $imgExt;
                $agent->addMediaFromRequest('additional_image')->usingFileName($imageName)->withResponsiveImages()->toMediaCollection('additional_images', 'agentFiles');
            }


            if ($request->hasFile('video')) {
                $agent->clearMediaCollection('videos');
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->title) . '.' . $ext;
                $agent->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'agentFiles');
            }

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $agent->approval_id = Auth::user()->id;

                if (in_array($request->is_approved, ["approved", "rejected"])) {
                    $agent->is_approved = $request->is_approved;
                }
            } else {
                $agent->is_approved = "requested";
                $agent->approval_id = null;
            }
            //$agent->updated_by = Auth::user()->id;
		$agent->department = $request->department;
            $agent->save();

            $url = config('app.frontend_url') . 'profile/' . Str::slug($agent->profileUrl) . '/' . $agent->slug;
            $qrCode = QrCode::format('png')->size(200)->generate($url);

            $imageName = $agent->slug . '.png';
            Storage::disk('agentQRFiles')->put($imageName, $qrCode);
            $qrCodeUrl = Storage::disk('agentQRFiles')->url($imageName);
            $agent->clearMediaCollection('QRs');
            $agent->addMediaFromUrl($qrCodeUrl)->usingFileName($imageName)->toMediaCollection('QRs', 'agentFiles');

$contact = [
                'version' => '3.0',
                'fn' =>$agent->name,
                'tel' => $agent->contact_number,
                'email' => $agent->email,
                'adr' => '1601, 16th Floor, Control Tower, Motor City, Dubai',
            ];

            $vcfContent = "BEGIN:VCARD\n";
            $vcfContent .= "VERSION:{$contact['version']}\n";
            $vcfContent .= "FN:{$contact['fn']}\n";
            $vcfContent .= "TEL:{$contact['tel']}\n";
            $vcfContent .= "EMAIL:{$contact['email']}\n";
            $vcfContent .= "ADR:{$contact['adr']}\n";
            $vcfContent .= "END:VCARD\n";

            $fileName = $agent->slug.'-contact.vcf';

            Storage::disk('agentCardFiles')->put($fileName, $vcfContent);


            $cardCodeUrl = Storage::disk('agentCardFiles')->url($fileName);
            $agent->clearMediaCollection('cards');
            $agent->addMediaFromUrl($cardCodeUrl)->usingFileName($fileName)->toMediaCollection('cards', 'agentFiles');


            if ($request->has('languageIds')) {
                $agent->languages()->detach();
                $agent->languages()->attach($request->languageIds);
            }
            if ($request->has('serviceIds')) {
                $agent->services()->detach();
                $agent->services()->attach($request->serviceIds);
            }
            if ($request->has('communityIds')) {
                $agent->communities()->detach();
                $agent->communities()->attach($request->communityIds);
            }
            if ($request->has('developerIds')) {
                $agent->developers()->detach();
                $agent->developers()->attach($request->developerIds);
            }
            if ($request->has('projectIds')) {
                $agent->projects()->detach();
                $agent->projects()->attach($request->projectIds);
            }
            return response()->json([
                'success' => true,
                'message' => 'Agent has been updated successfully.',
                'redirect' => route('dashboard.agents.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.agents.index'),
            ]);
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
        try {
            $agent = Agent::find($id);
            // foreach($agent->testimonals as $testimonal){
            //     $testimonal->status = config('constant.Inactive');
            //     $testimonal->save();
            // }
            $agent->delete();

            return redirect()->route('dashboard.agents.index')->with('success', 'Agent has been deleted successfully');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.agents.index')->with('error', $error->getMessage());
        }
    }
}
