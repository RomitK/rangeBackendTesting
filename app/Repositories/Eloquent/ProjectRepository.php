<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Facades\{
    DB,
    Log
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{
    ProjectAmenity,
    Project,
    Community,
    Property
};
use App\Jobs\{
    ProjectExportAndEmailData,
    StoreProjectBrochure
};


class ProjectRepository implements ProjectRepositoryInterface
{
    public function filterData($request)
    {

        $current_page = isset($request->item) ? $request->item : 25;
        $sr_no_start = isset($request->page) ? (($request->page * $current_page) - $current_page + 1) : 1;

        $collection = Project::mainProject()->with('user');

        if (isset($request->website_status)) {
            $collection->where('website_status', $request->website_status);
        }

        if (isset($request->data_range_input)) {
            $dateRange = $request->data_range_input;
            // Use explode to split the date range by " - "
            $dates = explode(' - ', $dateRange);
            // $startDate = Carbon::createFromFormat('F j, Y', $dates[0]);
            // $endDate = Carbon::createFromFormat('F j, Y', $dates[1])->endOfDay();


            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1])->endOfDay();


            $collection->whereBetween('created_at', [$startDate, $endDate]);
        }
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->is_valid)) {
            $collection->where('is_valid', $request->is_valid);
        }
        if (isset($request->qr_link)) {

            if ($request->qr_link == '1') {
                $collection->where('qr_link', '!=', '');
            } elseif ($request->qr_link == '0') {
                $collection->where('qr_link', '');
            }
        }
        if (isset($request->permit_number)) {
            if ($request->permit_number == '1') {
                $collection->whereNotNull('permit_number');
            } elseif ($request->permit_number == '0') {
                $collection->whereNull('permit_number');
            }
        }
        if (isset($request->completion_status_ids)) {
            $collection->whereIn('completion_status_id', $request->completion_status_ids);
        }

        if (isset($request->community_ids)) {
            $collection->whereIn('community_id', $request->community_ids);
        }
        if (isset($request->developer_ids)) {
            $collection->whereIn('developer_id', $request->developer_ids);
        }
        if (isset($request->updated_user_ids)) {
            $collection->whereIn('updated_by', $request->updated_user_ids);
        }

        if (isset($request->added_user_ids)) {
            $collection->whereIn('user_id', $request->added_user_ids);
        }
        if (isset($request->display_on_home)) {
            $collection->where('is_display_home', $request->display_on_home);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('projects.reference_number', 'like', "%$keyword%")
                    ->orWhere('projects.permit_number', 'like', "%$keyword%");
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }
        if (isset($request->updated_brochure)) {
            $collection->where('updated_brochure', $request->updated_brochure);
        }

        if(isset($request->project_source)){
            $collection->where('project_source', $request->project_source);
        }

        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $collection = $collection->orderByRaw('ISNULL(projectOrder)')->orderBy($orderBy, $direction);


            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                ProjectExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $projects = $collection->paginate($current_page);
            }
        } else {
            $collection = $collection->latest();

            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                ProjectExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $projects = $collection->paginate($current_page);
            }
        }
        return compact('projects', 'current_page', 'sr_no_start');
    }
    public function storeData($request)
    {
        try {

            $titleArray = explode(' ', $request->title);
            $sub_title = $titleArray[0];

            $subTitle1 = array_shift($titleArray);
            $sub_title_1 = implode(" ",  $titleArray);

            $project = new Project;

            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $project->is_approved = config('constants.approved');
                        $project->status = config('constants.active');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $project->is_approved = config('constants.approved');
                        $project->status = config('constants.Inactive');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $project->is_approved = config('constants.rejected');
                        $project->status = config('constants.active');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $project->is_approved = config('constants.requested');
                        $project->status = config('constants.active');
                        break;
                }
                $project->website_status = $request->website_status;
            } else {
                $project->is_approved = config('constants.requested');
                $project->status = config('constants.active');
                $project->website_status = $request->website_status;
            }

            $project->title = $request->title;
            $project->sub_title = $sub_title;
            $project->used_for = $request->used_for;
            $project->sub_title_1 = $sub_title_1;
            $project->project_source = 'crm';
            $project->is_parent_project = 1;
            $project->is_new_launch = $request->is_new_launch;
            $project->reference_number = $request->reference_number;
            $project->permit_number = $request->permit_number;
            $project->is_featured = $request->is_featured;
            $project->is_display_home = $request->is_display_home;
            $project->starting_price = $request->starting_price;
            $project->completion_date = $request->completion_date;
            $project->bathrooms = $request->bathrooms;
            $project->bedrooms = $request->bedrooms;
            $project->area = $request->area;
            $project->area_unit = $request->area_unit;
            $project->features_description = $request->features_description;
            $project->address = $request->address;
            $project->address_latitude = $request->address_latitude;
            $project->address_longitude = $request->address_longitude;
            $project->meta_title = $request->meta_title;
            $project->meta_description = $request->meta_description;
            $project->meta_keywords = $request->meta_keywords;
            $project->emirate = $request->emirate;

            if ($request->has('completion_status_id')) {
                $project->completionStatus()->associate($request->completion_status_id);
            }
            if ($request->has('starting_price_highlight')) {
                $project->starting_price_highlight = $request->starting_price_highlight;
            }
            if ($request->has('completion_date_highlight')) {
                $project->completion_date_highlight = $request->completion_date_highlight;
            }
            if ($request->has('area_highlight')) {

                $project->area_highlight = $request->area_highlight;
            }
            if ($request->has('accommodation_id')) {
                $project->accommodation_id = $request->accommodation_id;
            }
            if ($request->has('community_id_highlight')) {
                $project->community_id_highlight = $request->community_id_highlight;
            }
            if ($request->has('agent_id')) {
                $project->agent()->associate($request->agent_id);
            }
            if ($request->has('developer_id')) {
                $project->developer()->associate($request->developer_id);
            }
            if ($request->has('main_community_id')) {
                $project->mainCommunity()->associate($request->main_community_id);
            }

            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'projectFiles');
            }

            if ($request->hasFile('clusterPlan')) {
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'projectFiles');
            }

            if ($request->hasFile('qr')) {
                $img =  $request->file('qr');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '_qr.' . $ext;

                $project->addMediaFromRequest('qr')->usingFileName($imageName)->toMediaCollection('qrs', 'projectFiles');
            }



            if ($request->has('exteriorGallery')) {
                foreach ($request->exteriorGallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->title;
                        $order =  $img['order'] ?? null;

                        $project->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('exteriorGallery', 'projectFiles');
                    }
                }
            }

            if ($request->has('interiorGallery')) {
                foreach ($request->interiorGallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->title;
                        $order =  $img['order'] ?? null;

                        $project->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('interiorGallery', 'projectFiles');
                    }
                }
            }


            $project->short_description = $request->short_description;
            $project->long_description = $request->long_description;
            $project->user_id = Auth::user()->id;
            $project->projectOrder = $request->projectOrder;
            $project->save();

            $reference_prefix = 'RIPI_' . strtoupper(substr(Str::slug($project->developer->name), 0, 3));
            $nextInvoiceNumber = Project::getNextReferenceNumber($reference_prefix);
            $project->reference_number = $reference_prefix . "_" . $nextInvoiceNumber;

            $project->save();
            $project->banner_image = $project->mainImage;
            if ($request->hasFile('qr')) {
                $project->qr_link = $project->qr;
            }


            $project->save();


            if (!empty($project->permit_number) && !empty($project->qr_link)) {
                $project->is_valid = 1;
            } else {
                $project->is_valid = 0; // Optionally set to false if not valid
            }
            $project->save();

            $originalAttributes = $project->getOriginal();
            $originalAttributes['features_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->features_description))));
            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->short_description))));
            $originalAttributes['long_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->long_description))));

            if ($request->has('amenities')) {
                $project->amenities()->attach($request->amenities);
            } else {
                $originalAttributes['amenities'] = [];
            }

            if (in_array($request->website_status, [config('constants.available')])) {
                // StoreProjectBrochure::dispatch($project->id);
            }


            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Project has been created', $project->id, Project::class, $properties);

            // Return success response
            return [
                'success' => true,
                'message' => 'Project has been created successfully.',
                'project_id' => $project->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }

    
    public function updateData($request, $project)
    {
        DB::beginTransaction();
        try {
            // Get the original attributes before any updates
            $originalAttributes = $project->getOriginal();

            $originalAttributes['features_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->features_description))));
            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->short_description))));
            $originalAttributes['long_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->long_description))));


            if ($project->amenities) {
                $originalAttributes['amenities'] = $project->amenities->pluck('id')->toArray();
            } else {
                $originalAttributes['amenities'] = [];
            }


            $titleArray = explode(' ', $request->title);
            $sub_title = $titleArray[0];

            $subTitle1 = array_shift($titleArray);
            $sub_title_1 = implode(" ",  $titleArray);

            $project->title = $request->title;
            $project->sub_title = $sub_title;
            $project->sub_title_1 = $sub_title_1;
            $project->used_for = $request->used_for;
            $project->permit_number = $request->permit_number;
            $project->is_new_launch = $request->is_new_launch;
            $project->project_source = $request->project_source;
            $project->is_featured = $request->is_featured;
            $project->is_display_home = $request->is_display_home;
            $project->starting_price = $request->starting_price;
            $project->completion_date = $request->completion_date;
            $project->bathrooms = $request->bathrooms;
            $project->bedrooms = $request->bedrooms;
            $project->area = $request->area;
            $project->area_unit = $request->area_unit;
            $project->features_description = $request->features_description;
            $project->address = $request->address;
            $project->address_latitude = $request->address_latitude;
            $project->address_longitude = $request->address_longitude;
            $project->meta_title = $request->meta_title;
            $project->meta_description = $request->meta_description;
            $project->meta_keywords = $request->meta_keywords;
            $project->emirate = $request->emirate;
            $project->short_description = $request->short_description;
            $project->long_description = $request->long_description;
            $project->updated_by = Auth::user()->id;
            $project->projectOrder = $request->projectOrder;
            $project->updated_brochure = 0;

            if ($request->has('completion_status_id')) {
                $project->completionStatus()->associate($request->completion_status_id);
            }

            if ($request->has('agent_id')) {
                $project->agent()->associate($request->agent_id);
            }
            if ($request->has('developer_id')) {
                $project->developer()->associate($request->developer_id);
            }
            if ($request->has('main_community_id')) {
                $project->mainCommunity()->associate($request->main_community_id);
            }

            if ($request->has('starting_price_highlight')) {
                $project->starting_price_highlight = $request->starting_price_highlight;
            } else {
                $project->starting_price_highlight = 0;
            }
            if ($request->has('completion_date_highlight')) {
                $project->completion_date_highlight = $request->completion_date_highlight;
            } else {
                $project->completion_date_highlight = 0;
            }
            if ($request->has('community_id_highlight')) {
                $project->community_id_highlight = $request->community_id_highlight;
            } else {
                $project->community_id_highlight = 0;
            }
            if ($request->has('area_highlight')) {

                $project->area_highlight = $request->area_highlight;
            } else {
                $project->area_highlight = 0;
            }
            if ($request->has('accommodation_id_highlight')) {
                $project->accommodation_id_highlight = $request->accommodation_id_highlight;
            } else {
                $project->accommodation_id_highlight = 0;
            }
            if ($request->has('accommodation_id')) {
                $project->accommodation_id = $request->accommodation_id;
            }

            if ($request->hasFile('mainImage')) {
                $project->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'projectFiles');
            }

            if ($request->hasFile('clusterPlan')) {

                $project->clearMediaCollection('clusterPlans');
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'projectFiles');
            }

            if ($request->hasFile('qr')) {
                $project->clearMediaCollection('qrs');
                $img =  $request->file('qr');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '_qr.' . $ext;

                $project->addMediaFromRequest('qr')->usingFileName($imageName)->toMediaCollection('qrs', 'projectFiles');
            }

            if ($request->has('exteriorGallery')) {
                foreach ($request->exteriorGallery as $key => $img) {

                    $title = $img['title'] ?? $request->title;
                    $order =  $img['order'] ?? null;
                    if ($img['old_gallery_id'] > 0) {

                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $project->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])->toMediaCollection('exteriorGallery', 'projectFiles');
                        }
                    }
                }
            }

            if ($request->has('interiorGallery')) {
                foreach ($request->interiorGallery as $key => $img) {
                    $title = $img['title'] ?? $request->title;
                    $order =  $img['order'] ?? null;
                    if ($img['old_gallery_id'] > 0) {
                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $project->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('interiorGallery', 'projectFiles');
                        }
                    }
                }
            }

            // Handle website status and approval
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $project->is_approved = config('constants.approved');
                        $project->status = config('constants.active');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $project->is_approved = config('constants.approved');
                        $project->status = config('constants.inactive');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $project->is_approved = config('constants.rejected');
                        $project->status = config('constants.active');
                        $project->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                        $project->is_approved = config('constants.requested');
                        $project->status = config('constants.active');
                        $project->approval_id = null;
                        break;
                }
                $project->website_status = $request->website_status;
                $project->status = config('constants.active');
            } else {
                $project->is_approved = config('constants.requested');
                $project->website_status = $request->website_status;
                $project->status = config('constants.active');
                $project->approval_id = null;
            }

            $project->save();
            
            if($project->project_source == 'xml' && empty($project->reference_number)){
                $reference_prefix = 'RIPI_' . strtoupper(substr(Str::slug($project->developer->name), 0, 3));
                $nextInvoiceNumber = Project::getNextReferenceNumber($reference_prefix);
                $project->reference_number = $reference_prefix . "_" . $nextInvoiceNumber;
            }
            

            $project->banner_image = $project->mainImage;
            if ($request->hasFile('qr')) {
                $project->qr_link = $project->qr;
            }
            $project->save();

            if (!empty($project->permit_number) && !empty($project->qr_link)) {
                $project->is_valid = 1;
            } else {
                $project->is_valid = 0; // Optionally set to false if not valid
            }
            $project->save();


            // Retrieve the updated attributes
            $newProjectOriginalAttributes = $project->getOriginal();

            if ($request->has('amenities')) {
                $amenities = array_map('intval', $request->amenities);
                $project->amenities()->detach();
                $project->amenities()->attach($amenities);

                $newProjectOriginalAttributes['amenities'] = $amenities;
            } else {
                $project->amenities()->detach();
                $newProjectOriginalAttributes['amenities'] = [];
            }


            $project->subProjects()->update(['website_status' => $request->website_status]);
            Property::where('project_id', $project->id)->update(['updated_brochure' => 0]);

            if (in_array($request->website_status, [config('constants.available')])) {
                Log::info("project update for brochue");

                $this->makeXMLPropertiesUpdated($project);

                //StoreProjectBrochure::dispatch($project->id);

                $subProjects = $project->subProjects()->active()->where('website_status', config('constants.requested'))->pluck('id')->toArray();
                Project::whereIn('id', $subProjects)->update([
                    'approval_id' =>  $project->approval_id,
                    'is_approved' => $project->is_approved,
                    'updated_by' => $project->updated_by
                ]);
            }

            if (isset($project->features_description)) {
                $newProjectOriginalAttributes['features_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->features_description))));
            }
            if (isset($project->short_description)) {
                $newProjectOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->short_description))));
            }
            if (isset($project->long_description)) {
                $newProjectOriginalAttributes['long_description'] = trim(strip_tags(str_replace('&#13;', '', trim($project->long_description))));
            }

            $properties = $this->getUpdatedProperties($newProjectOriginalAttributes, $originalAttributes);

            logActivity('Project has been updated', $project->id, Project::class, $properties);

            // if ($project->is_valid == 1) {
                $this->makePropertiesUpdated($project);
            //}

            

            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Project has been updated successfully.',
                'project_id' => $project->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }

    public function makeXMLPropertiesUpdated($project)
    {
        try {

            $xmlProperties = Property::where('project_id', $project->id)
            ->where('property_source', 'xml')
            ->latest()->get();
            foreach ($xmlProperties as $property) {


                $originalAttributes = $property->getOriginal();
                $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                if ($property->amenities) {
                    $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $originalAttributes['amenityIds'] = [];
                }
               
                if (!empty($property->permit_number) && !empty($property->qr_link)) {
                    if($property->qr_link){
                        $property->is_valid = 1;
                    }else{
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }
                   
                } else {
                    $property->is_valid = 0; // Optionally set to false if not valid
                }
                $property->save();


                if($property->is_valid == 1 && $property->website_status == config('constants.requested')){
                    $property->status = config('constants.active');
                    $property->website_status = config('constants.available');
                }elseif($property->is_valid == 0 && $property->website_status == config('constants.requested')){
                    $property->status = config('constants.active');
                    $property->website_status = config('constants.NA');
                }
                
                $property->save();

                $newPropertyOriginalAttributes = $property->getOriginal();
                if ($property->amenities) {
                    $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $newPropertyOriginalAttributes['amenityIds'] = [];
                }

                if (isset($property->description)) {
                    $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                }
                if (isset($property->short_description)) {
                    $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                }

                $properties = $this->getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes);

                if($property->website_status == config('constants.available')){

                    logActivity('Property marked as Available as Permit Number and QR Exist', $property->id, Property::class, $properties);
                }elseif($property->website_status == config('constants.NA')){

                    logActivity('Property marked as NA due to missing of Permit Number and QR Exist', $property->id, Property::class, $properties);
                }else{
                    logActivity('Property Update as project updated', $property->id, Property::class, $properties);
                }
                
            }


        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function makePropertiesUpdated($project)
    {
        
        try {
            $properties = Property::where('project_id', $project->id)
            ->where('out_of_inventory', 0)
            ->where('property_source', 'crm')
            ->latest()->get();
            //dd($properties);
            foreach ($properties as $property) {


                $originalAttributes = $property->getOriginal();
                $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                if ($property->amenities) {
                    $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $originalAttributes['amenityIds'] = [];
                }

                $property->permit_number = $project->permit_number;

                if(!empty($project->qr_link)){
                    $property->addMediaFromUrl($project->qr_link)->toMediaCollection('qrs', 'propertyFiles' );
                }

                $property->save();
                if($property->qr){
                    $property->qr_link = $property->qr;
                }
                $property->save();

               
                if (!empty($property->permit_number) && !empty($property->qr_link)) {
                    if($property->qr_link){
                        $property->is_valid = 1;
                    }else{
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }
                   
                } else {
                    $property->is_valid = 0; // Optionally set to false if not valid
                }
                $property->save();
                if($property->is_valid == 1 && $property->website_status == config('constants.NA')){
                    $property->status = config('constants.active');
                    $property->website_status = config('constants.available');
                }elseif($property->is_valid == 0 && $property->website_status == config('constants.available')){
                    $property->status = config('constants.Inactive');
                    $property->website_status = config('constants.NA');
                }
                
                $property->save();

                $newPropertyOriginalAttributes = $property->getOriginal();
                if ($property->amenities) {
                    $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $newPropertyOriginalAttributes['amenityIds'] = [];
                }

                if (isset($property->description)) {
                    $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                }
                if (isset($property->short_description)) {
                    $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                }

                $properties = $this->getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes);

                if($property->website_status == config('constants.available')){

                    logActivity('Property marked as Available as Permit Number and QR Exist', $property->id, Property::class, $properties);
                }else{
                    logActivity('Property Update as project updated', $property->id, Property::class, $properties);
                }
                
            }
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function getUpdatedProperties($newProjectOriginalAttributes, $originalAttributes)
    {
        Log::info('newProjectOriginalAttributes', $newProjectOriginalAttributes);
        Log::info('originalAttributes', $originalAttributes);

        // Convert specific attributes to integer arrays if they exist
        $keysToConvert = ['developerIds', 'amenityIds', 'highlightIds'];

        foreach ($keysToConvert as $key) {
            if (isset($newProjectOriginalAttributes[$key]) && is_array($newProjectOriginalAttributes[$key])) {
                $newProjectOriginalAttributes[$key] = array_map('intval', $newProjectOriginalAttributes[$key]);
            }
            if (isset($originalAttributes[$key]) && is_array($originalAttributes[$key])) {
                $originalAttributes[$key] = array_map('intval', $originalAttributes[$key]);
            }
        }

        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newProjectOriginalAttributes as $key => $value) {
            if (!in_array($key, ['created_at', 'updated_at'])) {
                // Ensure the original attribute exists and compare based on type
                if (array_key_exists($key, $originalAttributes)) {
                    if (is_string($value) && $originalAttributes[$key] != $value) {
                        $updatedAttributes[$key] = $value;
                    } elseif (is_array($value) && serialize($originalAttributes[$key]) !== serialize($value)) {
                        $updatedAttributes[$key] = $value;
                    }
                } else {
                    // Handle case where $originalAttributes[$key] does not exist
                    $updatedAttributes[$key] = $value;
                }
            }
        }

        // Construct the updated attributes strings
        $updatedAttributesString = implode(', ', array_map(
            fn ($value, $key) => "$key: " . (is_array($value) ? json_encode($value) : $value),
            $updatedAttributes,
            array_keys($updatedAttributes)
        ));

        $updatedCoumnAttributesString = implode(', ', array_keys($updatedAttributes));

        // Encode the properties to JSON
        $properties = json_encode([
            'old' => $originalAttributes,
            'new' => $newProjectOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);

        return $properties;
    }

    public function updateMetaData($request, $project)
    {
        DB::beginTransaction();
        try {
            $originalAttributes = $project->getOriginal();

            $project->slug = $request->slug;
            $project->meta_title = $request->meta_title;
            $project->meta_description = $request->meta_description;
            $project->meta_keywords = $request->meta_keywords;
            $project->save();
            DB::commit();
            $newProjectOriginalAttributes = $project->getOriginal();

            $properties = $this->getUpdatedProperties($newProjectOriginalAttributes, $originalAttributes);

            logActivity('Project Meta Details has been updated', $project->id, Project::class, $properties);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Project Meta has been updated successfully.',
                'project_id' => $project->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function subProjectStore($request, $project)
    {
        try {

            $subProject = new Project;

            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.Inactive');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $subProject->is_approved = config('constants.rejected');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $subProject->is_approved = config('constants.requested');
                        $subProject->status = config('constants.active');
                        break;
                }
                $subProject->website_status = $request->website_status;
            } else {
                $subProject->is_approved = config('constants.requested');
                $subProject->status = config('constants.active');
                $subProject->website_status = $request->website_status;
            }


            $subProject->title = $request->title;
            $subProject->is_parent_project = 0;
            $subProject->parent_project_id = $project->id;
            $subProject->bedrooms = $request->bedrooms;
            $subProject->list_type = $request->list_type;
            $subProject->area = $request->area;
            $subProject->builtup_area = $request->builtup_area;
            $subProject->area_unit = $request->area_unit;
            $subProject->starting_price = $request->starting_price;
            $subProject->short_description = $request->short_description;
            $subProject->user_id = Auth::user()->id;
            $subProject->accommodation_id = $request->accommodation_id;
            $subProject->save();
            if ($request->hasFile('floorPlan')) {

                foreach ($request->floorPlan as $floorPlan) {
                    $subProject->addMedia($floorPlan)->toMediaCollection('floorPlans', 'projectFiles');
                }
            }


            $originalAttributes = $subProject->getOriginal();

            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('Sub Project has been created', $subProject->id, Project::class, $properties);

            // Return success response
            return [
                'success' => true,
                'message' => 'Sub Project has been created successfully.',
                //'project_id' => $subProject->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function subProjectUpdate($request, $project, $subProject)
    {

        DB::beginTransaction();
        try {
            // Get the original attributes before any updates
            $originalAttributes = $subProject->getOriginal();
            $subProject->title = $request->title;
            $subProject->status = $request->status;
            $subProject->is_parent_project = 0;
            $subProject->parent_project_id = $project->id;
            $subProject->bedrooms = $request->bedrooms;
            $subProject->area = $request->area;
            $subProject->builtup_area = $request->builtup_area;
            $subProject->area_unit = $request->area_unit;
            $subProject->list_type = $request->list_type;
            $subProject->starting_price = $request->starting_price;
            $subProject->accommodation_id = $request->accommodation_id;

            if ($request->hasFile('floorPlan')) {

                foreach ($request->floorPlan as $floorPlan) {
                    $subProject->addMedia($floorPlan)->toMediaCollection('floorPlans', 'projectFiles');
                }
            }
            // Handle website status and approval
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.inactive');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $subProject->is_approved = config('constants.rejected');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                        $subProject->is_approved = config('constants.requested');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = null;
                        break;
                }
                $subProject->website_status = $request->website_status;
                $subProject->status = config('constants.active');
            } else {
                $subProject->is_approved = config('constants.requested');
                $subProject->website_status = $request->website_status;
                $subProject->status = config('constants.active');
                $subProject->approval_id = null;
            }

            $subProject->save();

            // Retrieve the updated attributes
            $newSubProjectOriginalAttributes = $subProject->getOriginal();


            $properties = $this->getUpdatedProperties($newSubProjectOriginalAttributes, $originalAttributes);

            logActivity('Sub Project has been updated', $subProject->id, Project::class, $properties);

            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Sub Project has been updated successfully.',
                'project_id' => $project->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
}
