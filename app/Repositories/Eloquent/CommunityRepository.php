<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Repositories\Contracts\CommunityRepositoryInterface;
use Illuminate\Support\Facades\{
    DB,
    Log
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{
    Developer,
    Project,
    Community
};
use App\Jobs\{
    DeveloperExportAndEmailData,
    CommunityExportAndEmailData
};


class CommunityRepository implements CommunityRepositoryInterface
{
    public function filterData($request)
    {

        $current_page = isset($request->item) ? $request->item : 25;
        $sr_no_start = isset($request->page) ? (($request->page * $current_page) - $current_page + 1) : 1;


        $collection = Community::with(['user' => function ($query) {
            return $query->select('id', 'name');
        }]);
        if (isset($request->data_range_input)) {
            $dateRange = $request->data_range_input;
            // Use explode to split the date range by " - "
            $dates = explode(' - ', $dateRange);
            // $startDate = Carbon::createFromFormat('F j, Y', $dates[0]);
            // $endDate = Carbon::createFromFormat('F j, Y', $dates[1]);

            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1])->endOfDay();

            $collection->whereBetween('created_at', [$startDate, $endDate]);
        }
        if (isset($request->website_status)) {
            $collection->where('website_status', $request->website_status);
        }
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->display_on_home)) {
            $collection->where('display_on_home', $request->display_on_home);
        }
        if (isset($request->developer_ids)) {
            $developer_ids =  $request->developer_ids;

            $collection->whereHas('developers', function ($query) use ($developer_ids) {
                $query->whereIn('developers.id', $developer_ids);
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }
        if(isset($request->community_source)){
            $collection->where('community_source', $request->community_source);
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
            $collection = $collection->orderByRaw('ISNULL(communityOrder)')->orderBy($orderBy, $direction);

            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                CommunityExportAndEmailData::dispatch($request->all(), $collection->get());
                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $communities = $collection->paginate($current_page);
            }
        } else {
            $collection = $collection->latest();


            if (isset($request->export)) {
                $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);

                CommunityExportAndEmailData::dispatch($request->all(), $collection->get());

                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            } else {
                $communities = $collection->paginate($current_page);
            }
        }
        return compact('communities', 'current_page', 'sr_no_start');
    }
    public function storeData($request)
    {
        try {
            $community = new Community;
            $community->name = $request->name;
            $community->status = $request->status;
            $community->communityOrder = $request->communityOrder;
            $community->emirates = $request->emirates;
            $community->shortDescription = $request->shortDescription;
            $community->description = $request->description;
            $community->meta_title = $request->meta_title;
            $community->meta_description = $request->meta_description;
            $community->meta_keywords = $request->meta_keywords;
            $community->location_iframe = $request->location_iframe;
            $community->display_on_home = $request->display_on_home;
            $community->address = $request->address;
            $community->address_latitude = $request->address_latitude;
            $community->address_longitude = $request->address_longitude;


            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $community->is_approved = config('constants.approved');
                        $community->status = config('constants.active');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $community->is_approved = config('constants.approved');
                        $community->status = config('constants.Inactive');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $community->is_approved = config('constants.rejected');
                        $community->status = config('constants.active');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $community->is_approved = config('constants.requested');
                        $community->status = config('constants.active');
                        break;
                }
                $community->website_status = $request->website_status;
            } else {
                $community->is_approved = config('constants.requested');
                $community->status = config('constants.active');
                $community->website_status = $request->website_status;
            }

            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $community->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'commnityFiles');
            }

            if ($request->hasFile('listMainImage')) {
                $img =  $request->file('listMainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $community->addMediaFromRequest('listMainImage')->usingFileName($imageName)->toMediaCollection('listMainImages', 'commnityFiles');
            }


            if ($request->hasFile('clusterPlan')) {

                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $community->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'commnityFiles');
            }
            if ($request->has('gallery')) {
                foreach ($request->gallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;

                        $community->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('imageGalleries', 'commnityFiles');
                    }
                }
            }

            $community->user_id = Auth::user()->id;
            $community->updated_by = Auth::user()->id;
            $community->save();

            $community->banner_image = $community->mainImage;
            $community->listing_image = $community->listMainImage;
            $community->save();

            $originalAttributes = $community->getOriginal();

            $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($community->description))));


            if ($request->has('developerIds')) {
                $community->communityDevelopers()->attach($request->developerIds);
                $originalAttributes['developerIds'] = $request->developerIds;
            } else {
                $originalAttributes['developerIds'] = [];
            }
            if ($request->has('amenityIds')) {
                $community->amenities()->attach($request->amenityIds);
                $originalAttributes['amenityIds'] = $request->amenityIds;
            } else {
                $originalAttributes['amenityIds'] = [];
            }
            if ($request->has('highlightIds')) {
                $community->highlights()->attach($request->highlightIds);
                $originalAttributes['highlightIds'] = $request->highlightIds;
            } else {
                $originalAttributes['highlightIds'] = [];
            }




            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Community has been created', $community->id, Community::class, $properties);

            // Return success response
            return [
                'success' => true,
                'message' => 'Community has been created successfully.',
                'developer_id' => $community->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function updateData($request, $community)
    {
        DB::beginTransaction();
        try {
            // Get the original attributes before any updates
            $originalAttributes = $community->getOriginal();
            $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($community->description))));

            if ($community->communityDevelopers) {
                $originalAttributes['developerIds'] = $community->communityDevelopers->pluck('id')->toArray();
            } else {
                $originalAttributes['developerIds'] = [];
            }

            if ($community->amenities) {
                $originalAttributes['amenityIds'] = $community->amenities->pluck('id')->toArray();
            } else {
                $originalAttributes['amenityIds'] = [];
            }

            if ($community->highlights) {
                $originalAttributes['highlightIds'] = $community->highlights->pluck('id')->toArray();
            } else {
                $originalAttributes['highlightIds'] = [];
            }

            $community->name = $request->name;
            $community->communityOrder = $request->communityOrder;
            $community->emirates = $request->emirates;
            $community->shortDescription = $request->shortDescription;
            $community->description = $request->description;
            $community->meta_title = $request->meta_title;
            $community->meta_description = $request->meta_description;
            $community->meta_keywords = $request->meta_keywords;
            $community->location_iframe = $request->location_iframe;
            $community->display_on_home = $request->display_on_home;
            $community->address = $request->address;
            $community->address_latitude = $request->address_latitude;
            $community->address_longitude = $request->address_longitude;

            if ($request->hasFile('mainImage')) {
                $community->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $community->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'commnityFiles');
            }

            if ($request->hasFile('clusterPlan')) {
                $community->clearMediaCollection('clusterPlans');
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;
                $community->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'commnityFiles');
            }


            if ($request->has('gallery')) {
                foreach ($request->gallery as $img) {
                    $title = $img['title'] ?? $request->name;
                    $order =  $img['order'] ?? null;

                    if ($img['old_gallery_id'] > 0) {

                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $community->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('imageGalleries', 'commnityFiles');
                        }
                    }
                }
            }


            // Handle website status and approval
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $community->is_approved = config('constants.approved');
                        $community->status = config('constants.active');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $community->is_approved = config('constants.approved');
                        $community->status = config('constants.inactive');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $community->is_approved = config('constants.rejected');
                        $community->status = config('constants.active');
                        $community->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                        $community->is_approved = config('constants.requested');
                        $community->status = config('constants.active');
                        $community->approval_id = null;
                        break;
                }
                $community->website_status = $request->website_status;
                $community->status = config('constants.active');
            } else {
                $community->is_approved = config('constants.requested');
                $community->website_status = $request->website_status;
                $community->status = config('constants.active');
                $community->approval_id = null;
            }

            $community->updated_by = Auth::user()->id;
            $community->save();
            if ($request->hasFile('mainImage')) {
                $community->banner_image = $community->mainImage;
            }
            $community->save();
            // Retrieve the updated attributes
            $newCommunityOriginalAttributes = $community->getOriginal();


            if ($request->has('developerIds')) {
                $developerIds = array_map('intval', $request->developerIds);
                $community->communityDevelopers()->detach();
                $community->communityDevelopers()->attach($developerIds);

                $newCommunityOriginalAttributes['developerIds'] = $developerIds;
            } else {
                $community->communityDevelopers()->detach();
                $newCommunityOriginalAttributes['developerIds'] = [];
            }


            if ($request->has('amenityIds')) {
                $amenityIds = array_map('intval', $request->amenityIds);
                $community->amenities()->detach();
                $community->amenities()->attach($amenityIds);

                $newCommunityOriginalAttributes['amenityIds'] = $amenityIds;
            } else {
                $community->amenities()->detach();
                $newCommunityOriginalAttributes['amenityIds'] = [];
            }

            if ($request->has('highlightIds')) {
                $highlightIds = array_map('intval', $request->highlightIds);
                $community->highlights()->detach();
                $community->highlights()->attach($highlightIds);

                $newCommunityOriginalAttributes['highlightIds'] = $highlightIds;
            } else {
                $community->highlights()->detach();
                $newCommunityOriginalAttributes['highlightIds'] = [];
            }


            Project::where('community_id', $community->id)->update(['updated_brochure' => 0]);


            if (isset($community->description)) {
                $newCommunityOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($community->description))));
            }

            if ($request->hasFile('mainImage')) {
                $newCommunityOriginalAttributes['mainImage'] = $community->mainImage;
            }

            $properties = $this->getUpdatedProperties($newCommunityOriginalAttributes, $originalAttributes);

            logActivity('Community has been updated', $community->id, Community::class, $properties);


            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Community has been updated successfully.',
                'community_id' => $community->id,
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function getUpdatedProperties($newCommunityOriginalAttributes, $originalAttributes)
    {
        Log::info('newCommunityOriginalAttributes', $newCommunityOriginalAttributes);
        Log::info('originalAttributes', $originalAttributes);

        // Convert specific attributes to integer arrays if they exist
        $keysToConvert = ['developerIds', 'amenityIds', 'highlightIds'];

        foreach ($keysToConvert as $key) {
            if (isset($newCommunityOriginalAttributes[$key]) && is_array($newCommunityOriginalAttributes[$key])) {
                $newCommunityOriginalAttributes[$key] = array_map('intval', $newCommunityOriginalAttributes[$key]);
            }
            if (isset($originalAttributes[$key]) && is_array($originalAttributes[$key])) {
                $originalAttributes[$key] = array_map('intval', $originalAttributes[$key]);
            }
        }

        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newCommunityOriginalAttributes as $key => $value) {
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
            'new' => $newCommunityOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);

        return $properties;
    }



    public function updateMetaData($request, $community)
    {
        DB::beginTransaction();
        try {
            $originalAttributes = $community->getOriginal();

            $community->slug = $request->slug;
            $community->meta_title = $request->meta_title;
            $community->meta_description = $request->meta_description;
            $community->meta_keywords = $request->meta_keywords;
            $community->save();
            DB::commit();
            $newCommunityOriginalAttributes = $community->getOriginal();

            $properties = $this->getUpdatedProperties($newCommunityOriginalAttributes, $originalAttributes);

            logActivity('Community Meta Details has been updated', $community->id, Community::class, $properties);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Community Meta has been updated successfully.',
                'community_id' => $community->id,
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
