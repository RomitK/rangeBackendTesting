<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Requests\Dashboard\{
    PropertyRequest,
    PropertyMetaRequest
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Property,
    Amenity,
    Accommodation,
    Category,
    CompletionStatus,
    Community,
    Developer,
    Feature,
    OfferType,
    Specification,
    Agent,
    PropertyBedroom,
    PropertyDetail,
    Subcommunity,
    Project,
    User
};
use App\Jobs\{
    StorePropertyBrochure,
    StorePropertySaleOffer,
    PropertyExportAndEmailData
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class PropertyController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:'.config('constants.Permissions.xml_listings'), ['only' => ['index','create', 'edit', 'update', 'destroy']]);
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

        $collection = Property::with('developer', 'agent', 'category', 'user', 'project');

        if (isset($request->website_status)) {
            $collection->WebsiteStatus($request->website_status);
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
                $projects = $collection->paginate($current_page);
            }

            $properties = $collection->paginate($current_page);
        }

        //$properties = $collection->latest()->paginate($current_page);

        $agents = Agent::select('id', 'name')->latest()->get();
        $categories = Category::active()->latest()->pluck('name', 'id');
        $categories->prepend('All', '');
        $projects = Project::mainProject()->latest()->pluck('title', 'id');
        $completionStatuses = CompletionStatus::active()->where('for_property', 1)->latest()->pluck('name', 'id');
        $completionStatuses->prepend('All', '');
        $exclusiveOptions = [
            'All' => 'All',
            'non-exclusive' => 'Non-Exclusive',
            'exclusive' => 'Exclusive',
        ];
        $properties->appends($request->all());

        $users = User::latest()->pluck('name', 'id');
        $users->prepend('All', '');


        $accommodations = Accommodation::active()->pluck('name', 'id');
        $accommodations->prepend('All', '');

        return view('dashboard.realEstate.properties.index', compact(
            'properties',
            'completionStatuses',
            'projects',
            'current_page',
            'agents',
            'exclusiveOptions',
            'categories',
            'sr_no_start',
            'users',
            'accommodations'
        ));
    }

    public function updateBrochure(Property $property)
    {
        try {

            // $property = Property::where('slug', $slug)->first();
            view()->share(['property' => $property]);
            $pdf = PDF::loadView('pdf.propertyBrochure');
            $pdfContent = $pdf->output();

            $saleOffer = PDF::loadView('pdf.propertySaleOffer');
            $saleOfferPdf = $saleOffer->output();
            //return $saleOfferPdf->stream();

            $property->clearMediaCollection('brochures');
            $property->clearMediaCollection('saleOffers');


            $property->addMediaFromString($pdfContent)
                ->usingFileName($property->name . '-brochure.pdf')
                ->toMediaCollection('brochures', 'propertyFiles');

            $property->addMediaFromString($saleOfferPdf)
                ->usingFileName($property->name . '-saleoffer.pdf')
                ->toMediaCollection('saleOffers', 'propertyFiles');

            $property->save();
            $property->updated_brochure = 1;
            $property->save();


            DB::commit();

            $previousUrl = URL::previous();

            // Parse the URL to get the query part
            $parsedUrl = parse_url($previousUrl);
            $queryStr = $parsedUrl['query'] ?? '';

            // Parse the query string into an associative array
            parse_str($queryStr, $queryParams);



            return redirect()->route('dashboard.properties.index', $queryStr)->with('success', 'Property Brochure/SaleOffer has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.properties.index')->with('error', $error->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->get();
        $categories = Category::active()->latest()->get();
        $completionStatuses = CompletionStatus::active()->where('for_property', 1)->latest()->get();
        $communities = Community::latest()->get();
        $subCommunities = Subcommunity::active()->latest()->get();
        $developers = Developer::active()->latest()->get();
        $offerTypes = OfferType::active()->latest()->get();
        $agents = Agent::latest()->get();
        $projects = Project::with('developer')->mainProject()->latest()->get();
        $currencies = ['AED'];
        $bedrooms = ['Studio', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];


        $nextInvoiceNumber = Property::getNextReferenceNumber('RIPI');
        $nextReferenceNumber = Property::PROPERTY_REFERENCE_PREFIX . $nextInvoiceNumber;


        return view('dashboard.realEstate.properties.create', compact('projects', 'developers', 'bedrooms', 'currencies', 'agents', 'amenities', 'subCommunities', 'accommodations', 'categories', 'completionStatuses', 'communities', 'developers', 'offerTypes', 'nextReferenceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
        DB::beginTransaction();
        try {

            $property = new Property;
            $property->name = $request->name;
            $property->used_for = $request->used_for;
            $property->sub_title = $request->sub_title;
            $property->is_furniture = $request->is_furniture;
            $property->propertyOrder = $request->propertyOrder;
            // $property->reference_number = $nextReferenceNumber;
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
            //$property->cheque_frequency = $request->cheque_frequency;
            $property->status = $request->status;
            $property->is_feature = $request->is_feature;
            $property->exclusive = $request->exclusive;
            $property->property_source = 'crm';
            //$property->rating = $request->rating;
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
            if ($request->hasFile('qr')) {
                $img =  $request->file('qr');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '_qr.' . $ext;

                $property->addMediaFromRequest('qr')->usingFileName($imageName)->toMediaCollection('qrs', 'propertyFiles');
            }

            // if ($request->hasFile('subImages')) {
            //     foreach($request->subImages as $img)
            //     {
            //         $property->addMedia($img)->toMediaCollection('subImages', 'propertyFiles');
            //     }
            // }


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

            if ($request->hasFile('saleOffer')) {
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
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name) . '.' . $ext;
                $property->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'propertyFiles');
            }

            // if(in_array(Auth::user()->role, config('constants.isAdmin'))){
            //     $property->approval_id = Auth::user()->id;

            //     // if(in_array($request->is_approved, ["approved", "rejected"]) ){
            //         $property->is_approved = "approved";
            //     // }
            // }else{
            //     $property->is_approved = "requested";
            //     $property->approval_id = null;
            // }
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
            if ($request->has('amenityIds')) {
                $property->amenities()->attach($request->amenityIds);
            }

            Log::info("property store-time: " . Carbon::now());
            if (in_array($request->is_approved, [config('constants.approved')]) &&  in_array($request->status, [config('constants.active')])) {
                StorePropertyBrochure::dispatch($property->id);
                StorePropertySaleOffer::dispatch($property->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Property has been created successfully.',
                'redirect' => route('dashboard.properties.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.properties.index'),
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
    public function duplicateProperty(Property $property)
    {
        DB::beginTransaction();
        try {

            $clone = $property->replicate();
            $clone->is_approved = "requested";
            $clone->user_id = Auth::user()->id;
            $clone->updated_by =  Auth::user()->id;
            $clone->description = $property->description;
            $clone->push();
            $clone->description = $property->description->render();

            if ($property->mainImage) {

                $url = $property->mainImage;
                // Use pathinfo() to get the extension
                $extension = pathinfo($url, PATHINFO_EXTENSION);
                $imageName =  Str::slug($property->name) . '.' . $extension;
                $clone->addMediaFromUrl($property->mainImage)->usingFileName($imageName)->toMediaCollection('mainImages', 'propertyFiles');
            }

            // Duplicate gallery images
            foreach ($property->getMedia('subImages') as $media) {
                $clone->addMediaFromUrl($media->getUrl())
                    ->withCustomProperties([
                        'title' => $property->name,
                        'order' => $media->getCustomProperty('order')
                    ])->toMediaCollection('subImages', 'propertyFiles');
            }

            $clone->save();
            $clone->property_banner = $clone->property_banner;

            if ($property->category_id = 8) {
                $prefix = 'S';
            } else {
                $prefix = 'R';
            }
            $clone->reference_number = $this->generateUniqueCode($prefix);
            $clone->approval_id = null;
            $clone->save();


            if ($property->amenities) {
                $clone->amenities()->attach($property->amenities->pluck('id'));
            }

            DB::commit();
            return \Redirect::route('dashboard.properties.edit',  $clone->id)->with('success', 'Property has been duplicated successfully.');
        } catch (\Exception $error) {

            return \Redirect::route('dashboard.properties.index')->with('error', $error->getMessage());
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->get();
        $categories = Category::active()->latest()->get();
        $completionStatuses = CompletionStatus::active()->where('for_property', 1)->latest()->get();
        $communities = Community::with('subCommunities')->latest()->get();

        $offerTypes = OfferType::active()->latest()->get();
        $agents = Agent::latest()->get();
        $projects = Project::with('developer')->mainProject()->latest()->get();
        $currencies = ['AED'];
        $bedrooms = ['Studio', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

        return view('dashboard.realEstate.properties.edit', compact('projects', 'bedrooms', 'currencies', 'agents', 'property', 'amenities', 'accommodations', 'categories', 'completionStatuses', 'communities', 'offerTypes'));
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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyRequest $request, Property $property)
    {

        DB::beginTransaction();

        Log::info("Property-update-start:" . Carbon::now());


        try {
            $property->name = $request->name;
            $property->rental_period = $request->rental_period;
            $property->sub_title = $request->sub_title;
            $property->used_for = $request->used_for;
            $property->propertyOrder = $request->propertyOrder;
            $property->is_furniture = $request->is_furniture;
            $property->is_luxury_property = $request->is_luxury_property;
            $property->new_reference_number = $request->new_reference_number;
            $property->youtube_video = $request->youtube_video;
            // $property->reference_number = $request->reference_number;
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
            $property->status = $request->status;
            $property->is_feature = $request->is_feature;
            $property->exclusive = $request->exclusive;
            $property->property_source = $request->property_source;
            $property->rating = $request->rating;
            $property->primary_view = $request->primary_view;
            $property->is_display_home = $request->is_display_home;
            $property->address_longitude = $request->address_longitude;
            $property->address_latitude = $request->address_latitude;
            $property->address = $request->address;
            //$property->user_id = Auth::user()->id;
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
            if ($request->hasFile('qr')) {
                $property->clearMediaCollection('qrs');
                $img =  $request->file('qr');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '_qr.' . $ext;

                $property->addMediaFromRequest('qr')->usingFileName($imageName)->toMediaCollection('qrs', 'propertyFiles');
            }

            // if ($request->hasFile('subImages')) {

            //     foreach($request->subImages as $img)
            //     {
            //         $property->addMedia($img)->toMediaCollection('subImages', 'propertyFiles');
            //     }
            // }

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

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $property->approval_id = Auth::user()->id;

                if (in_array($request->is_approved, ["approved", "rejected"])) {
                    $property->is_approved = $request->is_approved;
                }
            } else {
                $property->is_approved = "requested";
                $property->approval_id = null;
            }

            $property->updated_by = Auth::user()->id;
            $property->save();
            $property->property_banner = $property->mainImage;

            // if(substr($property->category->name, 0, 1) == 'B'){
            //     $prefix = 'S';
            // }else{
            //     $prefix ='B';
            // }
            // $property->reference_number = $this->generateUniqueCode($prefix);
            // $property->save();

            $property->save();
            if ($request->has('amenityIds')) {
                $property->amenities()->detach();
                $property->amenities()->attach($request->amenityIds);
            }
            Log::info("property update-time: " . Carbon::now());
            if (in_array($request->is_approved, [config('constants.approved')]) &&  in_array($request->status, [config('constants.active')])) {
                StorePropertyBrochure::dispatch($property->id);
                StorePropertySaleOffer::dispatch($property->id);
            }



            DB::commit();

            Log::info("Property-update data:");

            Log::info(count($property->subImages));

            Log::info("Property-update-end:" . Carbon::now());

            return response()->json([
                'success' => true,
                'message' => 'Property has been updated successfully.',
                'redirect' => route('dashboard.properties.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.properties.index'),
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
            Property::find($id)->delete();

            return redirect()->route('dashboard.properties.index')->with('success', 'Property has been deleted successfully');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.properties.index')->with('error', $error->getMessage());
        }
    }

    public function meta(Property $property)
    {
        return view('dashboard.realEstate.properties.meta', compact('property'));
    }
    public function updateMeta(PropertyMetaRequest $request, Property $property)
    {
        DB::beginTransaction();
        try {
            $property->slug = $request->slug;
            $property->meta_title = $request->meta_title;
            $property->meta_description = $request->meta_description;
            $property->meta_keywords = $request->meta_keywords;
            $property->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Property Meta Detail has been created successfully',
                'redirect' => route('dashboard.properties.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.properties.index'),
            ]);
        }
    }
    public function mediaDestroy(Property $property, $media)
    {
        try {

            Log::info("mediaDestroy-start" . Carbon::now());
            Log::info("Property-" . $property->id . "Property-approval" . $property->is_approved);
            Log::info("media-" . $media);
            $property->deleteMedia($media);
            Log::info("mediaDestroy-end" . Carbon::now());


            return redirect()->route('dashboard.properties.edit', $property->id)->with('success', 'Property Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.properties.edit', $property->id)->with('error', $error->getMessage());
        }
    }
    public function mediasDestroy(Property $property)
    {
        try {
            $property->clearMediaCollection('subImages');
            return redirect()->route('dashboard.properties.edit', $property->id)->with('success', 'Property Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.properties.edit', $property->id)->with('error', $error->getMessage());
        }
    }
}
