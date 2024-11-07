<?php

namespace App\Http\Controllers\API;

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
    Project,
    WebsiteSetting,
    Property,
    Currency
};
use Auth;
use App\Http\Resources\{
    SingleManagementResource,
    SingleAgentResource,
    ManagementListResource,
    AgentListResource,
    
};
use PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AgentController extends Controller
{

    public function storeTeam(Request $request)
    {
        try {
            $data = $request->all();
            Log::info($data);
            Log::info('storeTeam start');

            $agent = new Agent;
            $agent->crm_id = $request->id;
            $agent->name = $request->full_name;
            $agent->status = 'Inactive';
            $agent->email = $request->email;
            $agent->is_display_home = 0;
            $agent->contact_number = $request->phone;
            $agent->whatsapp_number = $request->phone;
            $agent->employeeId = $request->employeeId;
            $agent->designation = $request->designation;
            $agent->department = $request->department;
            $agent->user_id = 1;
            $agent->save();


            foreach($request->languages as $key=>$language){
                if(Language::where('name', $language)->exists()){
                    $language = Language::where('name', $language)->first();
                    if ($language) {
                        $agent->languages()->attach($language->id);
                    }
                }else{
                    $language = new Language;
                    $language->name = $request->name;
                    $language->status = config('constants.active');
                    $language->user_id = 1;
                    $language->save();
                    $agent->languages()->attach($language->id);
                }
            }
            if($request->profile){
                $agent->addMediaFromUrl($request->profile)->withResponsiveImages()->toMediaCollection('images', 'agentFiles');
            }

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


            Log::info($agent);
            Log::info('storeTeam end');
            return $this->success('Store Team', $agent, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function managements(Request $request)
    {
        try {
            $managements = Agent::active()->where('is_management', 1)->orderBy('OrderBy', 'asc')->get();
            $managements =  ManagementListResource::collection($managements);
            return $this->success('All Management', $managements, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function checkEmployeeId(Request $request)
    {
        try {

                $currency = 'AED';
                $exchange_rate = 1;
                if(isset($request->currency)){
                    $currenyExist = Currency::where('name', $request->currency)->exists();
        
                    if($currenyExist){
                        $currency = $request->currency;
                        $exchange_rate = Currency::where('name', $request->currency)->first()->value;
                    }
                        
                }

                $link = null;
                if($request->formName == 'propertySaleOfferDownloadForm')
                {
                    $property = Property::where('slug', $request->property)->first();

                    Property::withoutTimestamps(function () use ($property, $currency, $exchange_rate) {
                        $property->saleoffer_link = null;
                        $property->save();
                        
                       
                        view()->share(['property' => $property, 
                            'currency' => $currency,
                            'exchange_rate' => $exchange_rate
                        ]);

    
                        $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                        $saleOfferPdf = $saleOffer->output();
                     
                        $property->clearMediaCollection('saleOffers');
    
                        $property->addMediaFromString($saleOfferPdf)
                            ->usingFileName($property->name . '-saleoffer.pdf')
                            ->toMediaCollection('saleOffers', 'propertyFiles');
    
                        $property->save();
                        $property->saleoffer_link = $property->saleOffer;
                        $property->save();
                            
                    });

                    $link = $property->saleoffer_link;
                }
                
            return $this->success('check Employee Id', ['verify' => true, 'link' => $link], 200);


        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleManagement($slug)
    {
        try {

            if (Agent::where('slug', $slug)->where('is_management', 1)->exists()) {
                $management = Agent::where('slug', $slug)->first();

                $singleManagement = (object)[];

                $singleManagement->id = "management_" . $management->id;
                $singleManagement->name = $management->name;
                $singleManagement->slug = $management->slug;
                $singleManagement->email = $management->email;
                $singleManagement->designation = $management->designation;
                $singleManagement->contact = $management->contact_number;
                $singleManagement->image = $management->image;
                $singleManagement->additionalImage = $management->additionalImage;
                $singleManagement->video = $management->video;
                $singleManagement->message = $management->message->render();

                if ($management->meta_title) {
                    $singleManagement->title = $management->meta_title;
                } else {
                    $singleManagement->title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($management->meta_description) {
                    $singleManagement->meta_description = $management->meta_description;
                } else {
                    $singleManagement->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($management->meta_keyword) {
                    $singleManagement->meta_keyword = $management->meta_keyword;
                } else {
                    $singleManagement->meta_keyword = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Management', $singleManagement, 200);
            } else {
                return $this->success('Single Management', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleManagementMeta($slug)
    {
        try {

            if (Agent::where('slug', $slug)->where('is_management', 1)->exists()) {
                $management = Agent::where('slug', $slug)->first();

                $singleManagement = (object)[];

                if ($management->meta_title) {
                    $singleManagement->title = $management->meta_title;
                } else {
                    $singleManagement->title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($management->meta_description) {
                    $singleManagement->meta_description = $management->meta_description;
                } else {
                    $singleManagement->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($management->meta_keywords) {
                    $singleManagement->meta_keywords = $management->meta_keywords;
                } else {
                    $singleManagement->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Management Meta', $singleManagement, 200);
            } else {
                return $this->success('Single Management', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleManagementDetail($slug)
    {
        try {

            if (Agent::where('slug', $slug)->where('is_management', 1)->exists()) {
                $management = Agent::where('slug', $slug)->first();
                $management = new SingleManagementResource($management);

                return $this->success('Single Management', $management, 200);
            } else {
                return $this->success('Single Management', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function agents(Request $request)
    {
        try {
            $agents = Agent::active()->where('is_management', 0)->orderBy('OrderBy', 'asc')->get();
            $agents =  AgentListResource::collection($agents);

            return $this->success('All Agents', $agents, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function profileTeam($slug)
    {
        try {

            if (Agent::where('slug', $slug)->exists()) {
                $management = Agent::where('slug', $slug)->first();
                $management = new SingleAgentResource($management);

                return $this->success('Single Team', $management, 200);
            } else {
                return $this->success('Single Team', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function agentLists(Request $request)
    {
        try {
            $agents = Agent::active()->where('is_management', 0)->orderBy('OrderBy', 'asc')->get()->map(function ($agent) {
                return [
                    'id' => 'agent_' . $agent->id,
                    'name' => $agent->name,
                    'slug' => $agent->slug,
                    'email' => $agent->email,
                    'contact' => $agent->contact_number,
                    'whatsapp' => $agent->whatsapp_number,
                    'image' => $agent->image,
                    'designation' => $agent->designation,
                    'languages' => $agent->languages->pluck('name')
                ];
            });
            return $this->success('All Agents', $agents, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
