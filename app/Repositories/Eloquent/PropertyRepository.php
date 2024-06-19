<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Support\Facades\{
    DB,
    Log
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{
    Developer,
    Project,
    Community,
    Property
};
use App\Jobs\{
    PropertyExportAndEmailData
};


class PropertyRepository implements PropertyRepositoryInterface
{
    public function filterData($request)
    {

        $current_page = isset($request->item) ? $request->item : 25;
        $sr_no_start = isset($request->page) ? (($request->page * $current_page) - $current_page + 1) : 1;


        $collection = Property::with('developer', 'agent', 'category', 'user', 'project');

        if (isset($request->website_status)) {
            $collection->where('website_status', $request->website_status);
        }
        if (isset($request->qr_link)) {

            if ($request->qr_link == '1') {
                $collection->whereHas('project', function ($query) {
                    $query->where('qr_link', '!=', '');
                });
            } elseif ($request->qr_link == '0') {
                $collection->whereHas('project', function ($query) {
                    $query->where('qr_link',  '');
                });
            }
        }
        if (isset($request->permit_number)) {
            if ($request->permit_number == '1') {
                $collection->whereHas('project', function ($query) {
                    $query->whereNotNull('permit_number');
                });
            } elseif ($request->permit_number == '0') {

                $collection->whereHas('project', function ($query) {
                    $query->whereNull('permit_number');
                });
            }
        }

        if (isset($request->data_range_input)) {
            $dateRange = $request->data_range_input;
            $dates = explode(' - ', $dateRange);
            // $startDate = Carbon::createFromFormat('F j, Y', $dates[0]);
            // $endDate = Carbon::createFromFormat('F j, Y', $dates[1]);

            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1])->endOfDay();

            $collection->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (isset($request->agent_ids) && !empty(array_filter($request->agent_ids))) {
            $agent_ids = $request->agent_ids;
            $collection->whereIn('agent_id', $agent_ids);
        }

        if (isset($request->project_ids) && !empty(array_filter($request->project_ids))) {
            $project_ids = $request->project_ids;
            $collection->whereIn('project_id', $project_ids);
        }

        if (isset($request->exclusive)) {
            if ($request->exclusive == 'exclusive') {
                $collection->where('exclusive', 1);
            } elseif ($request->exclusive == 'non-exclusive') {
                $collection->where('exclusive', 0);
            }
        }
        if (isset($request->is_duplicate)) {
            if ($request->is_duplicate == 'duplicate') {
                $collection->where('is_duplicate', 1);
            } elseif ($request->is_duplicate == 'not_duplicate') {
                $collection->where('is_duplicate', 0);
            }
        }

        if (isset($request->category_id)) {
            $collection->where('category_id', $request->category_id);
        }
        if (isset($request->is_furniture)) {
            $collection->where('is_furniture', $request->is_furniture);
        }

        if (isset($request->completion_status_id)) {
            $collection->where('completion_status_id', $request->completion_status_id);
        }

        if (isset($request->updated_user_ids)) {
            $collection->whereIn('updated_by', $request->updated_user_ids);
        }

        if (isset($request->added_user_ids)) {
            $collection->whereIn('user_id', $request->added_user_ids);
        }


        if (isset($request->accommodation_ids)) {
            $collection->whereIn('accommodation_id', $request->accommodation_ids);
        }

        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }

        if (isset($request->property_source)) {
            $collection->where('property_source', $request->property_source);
        }

        if (isset($request->keyword)) {

            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('properties.id', 'like', "%$keyword%")
                    ->orWhere('properties.name', 'like', "%$keyword%")
                    ->orWhere('properties.reference_number', 'like', "%$keyword%");
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }
        if (isset($request->updated_brochure)) {

            $collection->where('updated_brochure', $request->updated_brochure);
        }


        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $properties = $collection->orderByRaw('ISNULL(propertyOrder)')->orderBy($orderBy, $direction);


            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                PropertyExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $properties = $collection->paginate($current_page);
            }
        } else {
            $collection = $collection->latest();
            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                PropertyExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $properties = $collection->paginate($current_page);
            }
        }

        return compact('properties', 'current_page', 'sr_no_start');
    }
    public function generateUniqueCode($prefix)
    {
        //   do {
        //       $code = $prefix.random_int(1000000, 9999999);
        //   } while (Property::where("reference_number", 'like', "%$code%")->first());
        //   return $code;


        do {
            $code = $prefix . random_int(1000000, 9999999);
        } while (Property::where("reference_number", $code)->first());
        return $code;
    }
    public function storeData($request)
    {
        try {
            $property = new Property;

            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.Inactive');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $property->is_approved = config('constants.rejected');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $property->is_approved = config('constants.requested');
                        $property->status = config('constants.active');
                        break;
                }
                $property->website_status = $request->website_status;
            } else {
                $property->is_approved = config('constants.requested');
                $property->status = config('constants.active');
                $property->website_status = $request->website_status;
            }

            $property->name = $request->name;
            $property->used_for = $request->used_for;
            $property->sub_title = $request->sub_title;
            $property->is_furniture = $request->is_furniture;
            $property->propertyOrder = $request->propertyOrder;
            $property->emirate = $request->emirate;
            $property->permit_number = $request->permit_number;
            $property->meta_title = $request->meta_title;
            $property->meta_description = $request->meta_description;
            $property->meta_keywords = $request->meta_keywords;
            $property->short_description = $request->short_description;
            $property->description = $request->description;
            $property->bathrooms = $request->bathrooms;
            $property->bedrooms = $request->bedrooms;
            $property->area = $request->area;
            $property->builtup_area = $request->builtup_area;
            $property->is_luxury_property = $request->is_luxury_property;
            $property->youtube_video = $request->youtube_video;
            $property->parking_space = $request->parking_space;
            $property->price = $request->price;
            $property->rental_period = $request->rental_period;
            $property->is_feature = $request->is_feature;
            $property->exclusive = $request->exclusive;
            $property->property_source = 'crm';
            $property->primary_view = $request->primary_view;
            $property->is_display_home = $request->is_display_home;
            $property->address_longitude = $request->address_longitude;
            $property->address_latitude = $request->address_latitude;
            $property->new_reference_number = $request->new_reference_number;
            $property->address = $request->address;
            $property->user_id = Auth::user()->id;

            if ($request->has('accommodation_id')) {
                $property->accommodations()->associate($request->accommodation_id);
            }

            if ($request->has('community_id')) {
                $property->communities()->associate($request->community_id);
            }
            if ($request->has('developer_id')) {
                $property->developer()->associate($request->developer_id);
            }

            if ($request->has('sub_community_id')) {
                $property->subcommunities()->associate($request->sub_community_id);
            }

            if ($request->has('agent_id')) {
                $property->agent()->associate($request->agent_id);
            }
            if ($request->has('completion_status_id')) {
                $property->completionStatus()->associate($request->completion_status_id);
            }
            if ($request->has('developer_id')) {
                $property->developer()->associate($request->developer_id);
            }
            if ($request->has('project_id')) {
                $property->project()->associate($request->project_id);
            }
            if ($request->has('sub_project_id')) {
                $property->subProject()->associate($request->sub_project_id);
            }

            if ($request->has('offer_type_id')) {
                $property->offerType()->associate($request->offer_type_id);
            }
            if ($request->has('category_id')) {
                $property->category()->associate($request->category_id);
            }

            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;

                $property->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'propertyFiles');
            }

            if ($request->has('subImages')) {
                foreach ($request->subImages as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;

                        $property->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('subImages', 'propertyFiles');
                    }
                }
            }


            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name) . '.' . $ext;
                $property->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'propertyFiles');
            }

            $property->updated_by = Auth::user()->id;
            $property->save();

            $property->property_banner = $property->mainImage;
            $property->save();
            if ($property->category_id = 8) {
                $prefix = 'S';
            } else {
                $prefix = 'R';
            }
            $property->reference_number = $this->generateUniqueCode($prefix);
            $property->save();

            $originalAttributes = $property->getOriginal();

            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

            if ($request->has('amenityIds')) {
                $property->amenities()->attach($request->amenityIds);
                $originalAttributes['amenityIds'] = $request->amenityIds;
            } else {
                $originalAttributes['amenityIds'] = [];
            }

            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Property has been created', $property->id, Property::class, $properties);

            // Return success response
            return [
                'success' => true,
                'message' => 'Property has been created successfully.',
                'property_id' => $property->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function updateData($request, $property)
    {
        DB::beginTransaction();
        try {
            // Get the original attributes before any updates
            $originalAttributes = $property->getOriginal();
            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

            if ($property->amenities) {
                $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
            } else {
                $originalAttributes['amenityIds'] = [];
            }


            $property->name = $request->name;
            $property->rental_period = $request->rental_period;
            $property->sub_title = $request->sub_title;
            $property->used_for = $request->used_for;
            $property->propertyOrder = $request->propertyOrder;
            $property->is_furniture = $request->is_furniture;
            $property->is_luxury_property = $request->is_luxury_property;
            $property->new_reference_number = $request->new_reference_number;
            $property->youtube_video = $request->youtube_video;
            $property->emirate = $request->emirate;
            $property->permit_number = $request->permit_number;
            $property->meta_title = $request->meta_title;
            $property->meta_description = $request->meta_description;
            $property->meta_keywords = $request->meta_keywords;
            $property->short_description = $request->short_description;
            $property->description = $request->description;
            $property->bathrooms = $request->bathrooms;
            $property->bedrooms = $request->bedrooms;
            $property->area = $request->area;
            $property->builtup_area = $request->builtup_area;
            $property->parking_space = $request->parking_space;
            $property->price = $request->price;
            $property->cheque_frequency = $request->cheque_frequency;
            $property->is_feature = $request->is_feature;
            $property->exclusive = $request->exclusive;
            $property->property_source = $request->property_source;
            $property->rating = $request->rating;
            $property->primary_view = $request->primary_view;
            $property->is_display_home = $request->is_display_home;
            $property->address_longitude = $request->address_longitude;
            $property->address_latitude = $request->address_latitude;
            $property->address = $request->address;

            $property->updated_brochure = 0;
            if ($request->has('community_id')) {
                $property->community_id = $request->community_id;
            }

            if ($request->has('sub_community_id')) {
                $property->subcommunity_id = $request->sub_community_id;
            }
            if ($request->has('developer_id')) {
                $property->developer_id = $request->developer_id;
            }

            if ($request->has('agent_id')) {
                $property->agent_id = $request->agent_id;
            }

            $property->rating = $request->rating;
            $property->primary_view = $request->primary_view;

            if ($request->has('completion_status_id')) {
                $property->completion_status_id = $request->completion_status_id;
            }
            if ($request->has('project_id')) {
                $property->project_id = $request->project_id;
            }
            if ($request->has('sub_project_id')) {
                $property->subProject()->associate($request->sub_project_id);
            }
            if ($request->has('offer_type_id')) {
                $property->offer_type_id = $request->offer_type_id;
            }
            if ($request->has('category_id')) {
                $property->category_id = $request->category_id;
            }
            if ($request->has('accommodation_id')) {
                $property->accommodation_id = $request->accommodation_id;
            }

            if ($request->hasFile('mainImage')) {

                $property->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $property->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'propertyFiles');
            }

            if ($request->has('subImages')) {
                foreach ($request->subImages as $img) {
                    $title = $img['title'] ?? $request->name;
                    $order =  $img['order'] ?? null;

                    if ($img['old_gallery_id'] > 0) {

                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $property->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('subImages', 'propertyFiles');
                        }
                    }
                }
            }

            if ($request->hasFile('saleOffer')) {
                $property->clearMediaCollection('saleOffers');
                $img =  $request->file('saleOffer');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;

                $property->addMediaFromRequest('saleOffer')->usingFileName($imageName)->toMediaCollection('saleOffers', 'propertyFiles');
            }

            if ($request->hasFile('floorplans')) {
                foreach ($request->floorplans as $img) {
                    $property->addMedia($img)->toMediaCollection('floorplans', 'propertyFiles');
                }
            }

            if ($request->hasFile('video')) {
                $property->clearMediaCollection('videos');
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name) . '.' . $ext;
                $property->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'propertyFiles');
            }



            // Handle website status and approval
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.inactive');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $property->is_approved = config('constants.rejected');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                        $property->is_approved = config('constants.requested');
                        $property->status = config('constants.active');
                        $property->approval_id = null;
                        break;
                }
                $property->website_status = $request->website_status;
                $property->status = config('constants.active');
            } else {
                $property->is_approved = config('constants.requested');
                $property->website_status = $request->website_status;
                $property->status = config('constants.active');
                $property->approval_id = null;
            }

            $property->updated_by = Auth::user()->id;
            $property->save();



            if ($request->hasFile('mainImage')) {
                $property->property_banner = $property->mainImage;
            }
            $property->save();

            // Retrieve the updated attributes
            $newPropertyOriginalAttributes = $property->getOriginal();

            if ($request->has('amenityIds')) {
                $amenityIds = array_map('intval', $request->amenityIds);
                $property->amenities()->detach();
                $property->amenities()->attach($request->amenityIds);

                $newPropertyOriginalAttributes['amenityIds'] = $amenityIds;
            } else {
                $property->amenities()->detach();
                $newPropertyOriginalAttributes['amenityIds'] = [];
            }


            if (isset($property->description)) {
                $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
            }
            if (isset($property->short_description)) {
                $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            }

            $properties = $this->getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes);

            logActivity('Property has been updated', $property->id, Property::class, $properties);


            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Property has been updated successfully.',
                'property_id' => $property->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes)
    {
        Log::info('newPropertyOriginalAttributes', $newPropertyOriginalAttributes);
        Log::info('originalAttributes', $originalAttributes);

        // Convert specific attributes to integer arrays if they exist
        $keysToConvert = ['developerIds', 'amenityIds', 'highlightIds'];

        foreach ($keysToConvert as $key) {
            if (isset($newPropertyOriginalAttributes[$key]) && is_array($newPropertyOriginalAttributes[$key])) {
                $newPropertyOriginalAttributes[$key] = array_map('intval', $newPropertyOriginalAttributes[$key]);
            }
            if (isset($originalAttributes[$key]) && is_array($originalAttributes[$key])) {
                $originalAttributes[$key] = array_map('intval', $originalAttributes[$key]);
            }
        }

        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newPropertyOriginalAttributes as $key => $value) {
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
            'new' => $newPropertyOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);

        return $properties;
    }



    public function updateMetaData($request, $property)
    {
        DB::beginTransaction();
        try {
            $originalAttributes = $property->getOriginal();

            $property->slug = $request->slug;
            $property->meta_title = $request->meta_title;
            $property->meta_description = $request->meta_description;
            $property->meta_keywords = $request->meta_keywords;
            $property->save();
            DB::commit();
            $newPropertyOriginalAttributes = $property->getOriginal();

            $properties = $this->getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes);

            logActivity('Property Meta Details has been updated', $property->id, Property::class, $properties);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Property Meta has been updated successfully.',
                'property_id' => $property->id,
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
