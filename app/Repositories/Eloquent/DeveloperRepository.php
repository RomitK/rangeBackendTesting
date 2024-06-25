<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Repositories\Contracts\DeveloperRepositoryInterface;
use Illuminate\Support\Facades\{
    DB,
    Log
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{
    Developer,
    Project
};
use App\Jobs\{
    DeveloperExportAndEmailData
};


class DeveloperRepository implements DeveloperRepositoryInterface
{
    public function filterData($request)
    {

        $current_page = isset($request->item) ? $request->item : 25;
        $sr_no_start = isset($request->page) ? (($request->page * $current_page) - $current_page + 1) : 1;

        $collection = Developer::with(['user', 'approval']);

        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->website_status)) {
            $collection->where('website_status', $request->website_status);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }
        if (isset($request->data_range_input)) {
            $dateRange = $request->data_range_input;
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1])->endOfDay();
            $collection->whereBetween('created_at', [$startDate, $endDate]);
        }
        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at');
            $direction = $request->input('direction', 'asc');
            $collection = $collection->orderByRaw('ISNULL(developerOrder)')->orderBy($orderBy, $direction);
        } else {
            $collection = $collection->latest();
        }

        if (isset($request->export)) {
            $request->merge(['email' => Auth::user()->email, 'userName' => Auth::user()->name]);
            DeveloperExportAndEmailData::dispatch($request->all(), $collection->get());
            return response()->json([
                'success' => true,
                'message' => 'Please Check Email, Report has been sent.',
            ]);
        } else {
            $developers = $collection->paginate($current_page);
        }
        return compact('developers', 'current_page', 'sr_no_start');
    }
    public function storeData($request)
    {
        try {
            $developer = new Developer;
            // Set attributes based on request data
            $developer->name = $request->name;
            $developer->display_on_home = $request->display_on_home;
            $developer->developerOrder = $request->developerOrder;
            $developer->user_id = Auth::user()->id;
            $developer->short_description = $request->short_description;
            $developer->long_description = $request->long_description;
            $developer->meta_title = $request->meta_title;
            $developer->meta_description = $request->meta_description;
            $developer->meta_keywords = $request->meta_keywords;

            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $developer->is_approved = config('constants.approved');
                        $developer->status = config('constants.active');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $developer->is_approved = config('constants.approved');
                        $developer->status = config('constants.Inactive');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $developer->is_approved = config('constants.rejected');
                        $developer->status = config('constants.active');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $developer->is_approved = config('constants.requested');
                        $developer->status = config('constants.active');
                        break;
                }
                $developer->website_status = $request->website_status;
            } else {
                $developer->is_approved = config('constants.requested');
                $developer->status = config('constants.active');
                $developer->website_status = $request->website_status;
            }

            // Handle file uploads (logo, image, gallery)
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $ext = $logo->getClientOriginalExtension();
                $logoName = Str::slug($request->name) . '_logo.' . $ext;
                $developer->addMediaFromRequest('logo')->usingFileName($logoName)->toMediaCollection('logos', 'developerFiles');
            }
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName = Str::slug($request->name) . '_image.' . $ext;
                $developer->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'developerFiles');
            }
            if ($request->has('gallery')) {
                foreach ($request->gallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->name;
                        $order = $img['order'] ?? null;
                        $developer->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('gallery', 'developerFiles');
                    }
                }
            }

            // Save developer data
            $developer->updated_by = Auth::user()->id;
            $developer->save();

            // Optionally update developer's logo_image attribute
            $developer->logo_image = $developer->logo;
            $developer->save();

            $originalAttributes = $developer->getOriginal();
            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($developer->short_description))));

            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Developer has been created', $developer->id, Developer::class, $properties);

            // Return success response
            return [
                'success' => true,
                'message' => 'Developer has been created successfully.',
                'developer_id' => $developer->id, // Optionally return the created developer ID
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function updateData($request, $developer)
    {
        DB::beginTransaction();
        try {
            // Get the original attributes before any updates
            $originalAttributes = $developer->getOriginal();
            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($developer->short_description))));


            // Update developer's attributes from the request
            $developer->name = $request->name;
            $developer->status = $request->status;
            $developer->display_on_home = $request->display_on_home;
            $developer->developerOrder = $request->developerOrder;
            $developer->short_description = $request->short_description;
            $developer->meta_title = $request->meta_title;
            $developer->meta_description = $request->meta_description;
            $developer->meta_keywords = $request->meta_keywords;

            if ($request->hasFile('logo')) {
                $developer->clearMediaCollection('logos');
                $logo =  $request->file('logo');
                $ext = $logo->getClientOriginalExtension();
                $logoName =  Str::slug($request->name) . '_logo.' . $ext;
                $developer->addMediaFromRequest('logo')->usingFileName($logoName)->toMediaCollection('logos', 'developerFiles');
            }
            if ($request->hasFile('image')) {
                $developer->clearMediaCollection('images');
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '_image.' . $ext;
                $developer->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'developerFiles');
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
                            $developer->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('gallery', 'developerFiles');
                        }
                    }
                }
            }

            // Handle website status and approval
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $developer->is_approved = config('constants.approved');
                        $developer->status = config('constants.active');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $developer->is_approved = config('constants.approved');
                        $developer->status = config('constants.inactive');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $developer->is_approved = config('constants.rejected');
                        $developer->status = config('constants.active');
                        $developer->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                        $developer->is_approved = config('constants.requested');
                        $developer->status = config('constants.active');
                        $developer->approval_id = null;
                        break;
                }
                $developer->website_status = $request->website_status;
            } else {
                $developer->is_approved = config('constants.requested');
                $developer->status = config('constants.active');
                $developer->website_status = $request->website_status;
                $developer->approval_id = null;
            }

            $developer->updated_by = Auth::user()->id;
            $developer->save();

            if ($request->hasFile('logo')) {
                $developer->logo_image = $developer->logo;
                $developer->save();
            }



            // Retrieve the updated attributes
            $newDeveloperOriginalAttributes = $developer->getOriginal();

            if (isset($developer->short_description)) {
                $newDeveloperOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($developer->short_description))));
            }
            if ($request->hasFile('logo')) {
                $newDeveloperOriginalAttributes['logo_image'] = $developer->logo;
            }

            $properties = $this->getUpdatedProperties($newDeveloperOriginalAttributes, $originalAttributes);


            logActivity('Developer has been updated', $developer->id, Developer::class, $properties);

            // Update related projects
            Project::where('developer_id', $developer->id)->update(['updated_brochure' => 0]);
            DB::commit();

            // Return success response
            return [
                'success' => true,
                'message' => 'Developer has been updated successfully.',
                //'developer_id' => $developer->id, // Optionally return the created developer ID
            ];
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function getUpdatedProperties($newDeveloperOriginalAttributes, $originalAttributes)
    {
        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newDeveloperOriginalAttributes as $key => $value) {
            if (!in_array($key, ['created_at', 'updated_at'])) {

                if ($originalAttributes[$key] != $value) {
                    $updatedAttributes[$key] = $value;
                }
            }
        }

        $updatedAttributesString = implode(', ', array_map(
            fn ($value, $key) => "$key: $value",
            $updatedAttributes,
            array_keys($updatedAttributes)
        ));


        $updatedCoumnAttributesString = implode(', ', array_map(
            fn ($value, $key) => "$key",
            $updatedAttributes,
            array_keys($updatedAttributes)
        ));


        $properties = json_encode([
            'old' => $originalAttributes,
            'new' => $newDeveloperOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);
        return $properties;
    }
    public function updateMetaData($request, $developer)
    {
        DB::beginTransaction();
        try {
            $originalAttributes = $developer->getOriginal();

            $developer->slug = $request->slug;
            $developer->meta_title = $request->meta_title;
            $developer->meta_description = $request->meta_description;
            $developer->meta_keywords = $request->meta_keywords;
            $developer->save();
            DB::commit();
            $newDeveloperOriginalAttributes = $developer->getOriginal();

            $properties = $this->getUpdatedProperties($newDeveloperOriginalAttributes, $originalAttributes);

            logActivity('Developer Meta Details has been updated', $developer->id, Developer::class, $properties);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Developer Meta has been updated successfully.',
                'developer_id' => $developer->id, // Optionally return the created developer ID
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
