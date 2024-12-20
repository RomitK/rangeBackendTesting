<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;
use App\Observers\PropertyObserver;
use Illuminate\Support\Facades\DB;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;
    const PROPERTY_REFERENCE_PREFIX = "RIPI_";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // public $timestamps = false; // Set to false to disable automatic timestamping
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'sub_title',
        'status',
        'bathrooms',
        'built_area',
        'unit_measure',
        'slug',
        'is_feature',
        'user_id',
        'offer_type_id',
        'developer_id',
        'completion_status_id',
        'category_id',
        'reference_number',
        'permit_number',
        'parking',
        'price',
        'address_longitude',
        'address_latitude',
        'property_source',
        'emirate',
        'rating',
        'project_id',
    ];
    /**
     * The dates attributes
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'description',
        'features_description',
        'amenties_description'
    ];

    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'qr',
        'video',
        'mainImage',
        'floorplans',
        'saleoffer',
        'subImages',
        'brochure',
        'formattedCreatedAt',
        'formattedUpdatedAt'
    ];

    public $registerMediaConversionsUsingModelInstance = true;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    /**
     * SET Attributes
     */

    public function getInvoiceNumAttribute()
    {
        $position = $this->strposX($this->reference_number, "-", 1) + 1;
        return substr($this->reference_number, $position);
    }
    public function getInvoicePrefixAttribute()
    {
        $prefix = explode("-", $this->new_reference_number)[0];
        return $prefix;
    }
    public static function getNextReferenceNumber($value)
    {
        // Get the last created order
        $lastOrder = Property::where('reference_number', 'LIKE', $value . '_%')
            ->orderBy('id', 'desc')
            ->first();


        if (!$lastOrder) {
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            $number = explode("_", $lastOrder->reference_number);
            $number = $number[1];
        }
        // If we have ORD000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %06d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return sprintf('%07d', intval($number) + 1);
    }

    /**
     * GET Attributes
     */

    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }

    public function getQrAttribute()
    {
        return $this->getFirstMediaUrl('qrs', 'resize');
    }



    public function getFloorplansAttribute()
    {
        $floorplans = array();
        foreach ($this->getMedia('floorplans') as $image) {

            array_push($floorplans, ['id' => $image->id, 'path' => $image->getUrl()]);
        }
        return $floorplans;
    }


    public function getMainImageAttribute()
    {
        // $mediaItems = $this->getMedia('mainImages');

        // if ($mediaItems->isNotEmpty()) {
        //     $lastMediaItem = $mediaItems->last();
        //     if (url_exists($lastMediaItem->getUrl('resize'))) {
        //         return $lastMediaItem->getUrl('resize');
        //     }
        // }

        // return asset('frontend/assets/images/no-image.webp');

        if (url_exists($this->getFirstMediaUrl('mainImages', 'resize'))) {
            return $this->getFirstMediaUrl('mainImages', 'resize');
        } else {
            return asset('frontend/assets/images/no-image.webp');
        }

        //return $this->getFirstMediaUrl('mainImages', 'resize');
    }
    public function getSubImagesAttribute()
    {
        //     $subImages = array();
        //     foreach($this->getMedia('subImages') as $image){
        //         array_push($subImages, ['id'=> $image->id, 'path'=>$image->getUrl(), 'title' => $this->name, 'order' => null]);
        //     }
        //   return $subImages;

        $gallery = array();
        foreach ($this->getMedia('subImages')->sortBy(function ($mediaItem, $key) {
            $order = $mediaItem->getCustomProperty('order');
            return $order ?? PHP_INT_MAX;
        }) as $image) {
            if ($image->hasGeneratedConversion('resize_images')) {
                array_push(
                    $gallery,
                    [
                        'id' => $image->id,
                        'path' => $image->getUrl('resize_images'),
                        'title' => $image->getCustomProperty('title'), // Get the 'title' custom property
                        'order' => $image->getCustomProperty('order'), // Get the 'order' custom property
                    ]
                );
            } else {
                array_push(
                    $gallery,
                    [
                        'id' => $image->id,
                        'path' => $image->getUrl(),
                        'title' => $image->getCustomProperty('title'), // Get the 'title' custom property
                        'order' => $image->getCustomProperty('order'), // Get the 'order' custom property
                    ]
                );
            }
        }
        return $gallery;
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $propertySource = $this->property_source ?? '';

        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            //->height(300)
            // ->watermark(public_path('frontend/assets/images/range-r.png'))
            // ->watermarkPosition(Manipulations::POSITION_CENTER)
            // ->watermarkHeight(20, Manipulations::UNIT_PERCENT)
            // ->watermarkOpacity(60)

            // ->watermark(public_path('frontend/assets/images/range_blue.png'))
            // ->watermarkPosition(Manipulations::POSITION_CENTER)
            // ->watermarkHeight(15, Manipulations::UNIT_PERCENT)
            // ->watermarkOpacity(50)
            ->performOnCollections('mainImages')
            ->nonQueued();

        if($propertySource == 'crm'){
            $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            ->height(400)
            ->watermark(public_path('frontend/assets/images/range_blue.png'))
            ->watermarkPosition(Manipulations::POSITION_CENTER)
            ->watermarkHeight(10, Manipulations::UNIT_PERCENT)
            ->watermarkOpacity(50)
            ->performOnCollections('subImages')
            ->nonQueued();
        }else{
            $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            ->height(400)
            ->performOnCollections('subImages')
            ->nonQueued();
        }





        // $this->addMediaConversion('resize')
        //     ->format(Manipulations::FORMAT_WEBP)
        //     //->width(800)
        //     ->height(300)
        //     ->watermark(public_path('frontend/assets/images/logo_white.png'))
        //   // 20 pixels padding from the right, 10 pixels from the bottom
        //     ->watermarkPadding(140, 10, Manipulations::UNIT_PIXELS, Manipulations::POSITION_BOTTOM_RIGHT)
        //     ->watermarkHeight(8, Manipulations::UNIT_PERCENT)
        //     ->watermarkOpacity(60)
        //     ->performOnCollections('mainImages')
        //     ->nonQueued();

        // $this->addMediaConversion('resize_images')
        //     ->format(Manipulations::FORMAT_WEBP)
        //     //->width(800)
        //     ->height(800)
        //     ->performOnCollections('subImages')
        //     ->nonQueued();



        // $this->addMediaConversion('resize_images')
        //     ->format(Manipulations::FORMAT_WEBP)
        //     //->width(800)
        //     ->height(400)
        //     ->watermark(public_path('frontend/assets/images/logo_white.png'))
        //      ->watermarkPosition(Manipulations::POSITION_TOP_LEFT)
        //     ->watermarkPadding(80, 20, Manipulations::UNIT_PIXELS)
        //     ->watermarkHeight(8, Manipulations::UNIT_PERCENT)
        //     ->watermarkOpacity(60)

        //     ->performOnCollections('subImages')
        //     ->nonQueued();

        // $this->addMediaConversion('resize_qr_images')
        // ->format(Manipulations::FORMAT_WEBP)
        // ->performOnCollections('qrs')
        // ->nonQueued();
    }
    // public function getFloorPlanAttribute()
    // {
    //     return $this->getFirstMediaUrl('floorPlans');
    // }
    public function getBrochureAttribute()
    {
        return $this->getFirstMediaUrl('brochures');
    }
    public function getSaleOfferAttribute()
    {
        return $this->getFirstMediaUrl('saleOffers');
    }

    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }

    public function getFormattedUpdatedAtAttribute($value)
    {
        return Carbon::parse($this->updated_at)->format('d m Y');
    }
    /**
     * FIND Relationship
     */
    public function approval()
    {
        return $this->belongsTo(User::class, 'approval_id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offerType()
    {
        return $this->belongsTo(OfferType::class, 'offer_type_id', 'id');
    }
    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function subProject()
    {
        return $this->belongsTo(Project::class, 'sub_project_id', 'id');
    }


    public function communities()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
    public function subcommunities()
    {
        return $this->belongsTo(Subcommunity::class, 'subcommunity_id', 'id');
    }
    public function completionStatus()
    {
        return $this->belongsTo(CompletionStatus::class, 'completion_status_id', 'id');
    }
    public function accommodations()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id', 'id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities', 'property_id', 'amenity_id');
    }
    public function propertygallery()
    {
        return $this->hasMany(PropertyGallery::class, 'property_id', 'id');
    }

    // public function propertygallery()
    // {
    //     return $this->belongsToMany(PropertyGallery::class, 'propertygallery', 'property_id', 'galleryimage');
    // }


    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
    public function details()
    {
        return $this->hasMany(PropertyDetail::class);
    }
    public function bedroomss()
    {
        return $this->hasMany(PropertyBedroom::class, 'property_id', 'id');
    }
    public function propertyDetails()
    {
        return $this->hasMany(PropertyDetail::class, 'property_id', 'id');
    }
    public function imagegalleries()
    {
        return $this->hasMany(Imagegallery::class, 'property_id', 'id');
    }
    public function logActivity()
    {
        return $this->hasMany(LogActivity::class, 'subject_id', 'id')->orderBy('id', 'desc');
    }
    /**
     * FIND local scope
     */
    public function scopeRejected($query)
    {
        return $query->where('is_approved', config('constants.rejected'));
    }
    public function scopeRequested($query)
    {
        return $query->where('is_approved', config('constants.requested'));
    }
    public function scopeApproved($query)
    {
        return $query->where('is_approved', config('constants.approved'));
    }
    public function scopeActive($query)
    {
        return $query->where('status', config('constants.active'));
    }
    public function CrmProperties($query)
    {
        return $query->where('property_source', 'crm');
    }
    public function XmlProperties($query)
    {
        return $query->where('property_source', 'xml');
    }
    public function scopeAvailable($query)
    {
        return $query->where('website_status', config('constants.available'));
    }
    public function scopeDeactive($query)
    {
        return $query->where('status',  config('constants.Inactive'));
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeBuy($query)
    {
        return $query->where('category_id', 8);
    }
    public function scopeRent($query)
    {
        return $query->where('category_id', 9);
    }

    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('properties')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }
    public static function getCountsByAgent($startDate, $endDate)
    {
        $propertyAgentWiseCount = DB::table('agents')
            ->select(
                'agents.name as agent_name',
                DB::raw('IFNULL(SUM(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 286 AND  properties.website_status = "available" THEN 1 ELSE 0 END), 0) as ready'),
                DB::raw('IFNULL(SUM(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 287 AND properties.website_status = "available" THEN 1 ELSE 0 END), 0) as offplan'),
                DB::raw('IFNULL(SUM(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 291 AND  properties.website_status = "available" THEN 1 ELSE 0 END), 0) as offplan_resale'),
                DB::raw('IFNULL(SUM(CASE WHEN properties.category_id = 9 AND properties.website_status = "available" THEN 1 ELSE 0 END), 0) as rent')
            )
            ->leftJoin('properties', 'agents.id', '=', 'properties.agent_id')
            ->whereNotNull('properties.agent_id') // Filter agents with properties
            ->whereNull('properties.deleted_at')
            ->whereNull('agents.deleted_at')
            ->whereBetween('properties.created_at', [$startDate, $endDate])
            ->groupBy('agents.name')
            ->get();

        $result = [];

        foreach ($propertyAgentWiseCount as $agent) {
            $result[] = [
                'agent_name' => $agent->agent_name,
                'ready' => $agent->ready ?? 0,
                'offplan' => $agent->offplan ?? 0,
                'offplan_resale' => $agent->offplan_resale ?? 0,
                'rent' => $agent->rent ?? 0,
            ];
        }

        return $result;
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('properties')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN status = "active" AND is_approved = "approved" THEN 1 END) as available,
                COUNT(CASE WHEN status = "inactive" AND is_approved = "approved" THEN 1 END) as NA,
                COUNT(CASE WHEN is_approved = "rejected" THEN 1 END) as rejected,
                COUNT(CASE WHEN is_approved = "requested" THEN 1 END) as requested
            ')
            ->first();
    }
    public static function getCountsByWebsiteStatus($startDate, $endDate)
    {
        return DB::table('properties')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN website_status = "available" THEN 1 END) as available,
                COUNT(CASE WHEN website_status = "NA" THEN 1 END) as NA,
                COUNT(CASE WHEN website_status = "rejected" THEN 1 END) as rejected,
                COUNT(CASE WHEN website_status = "requested" THEN 1 END) as requested
            ')
            ->first();
    }
    public static function getCountsByApprovalStatus($startDate, $endDate)
    {
        return DB::table('properties')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN is_approved = "requested" THEN 1 END) as requested,
                COUNT(CASE WHEN is_approved = "approved" THEN 1 END) as approved,
                COUNT(CASE WHEN is_approved = "rejected" THEN 1 END) as rejected
            ')
            ->first();
    }

    public static function getCountsByPermitNumber($startDate, $endDate)
    {

        return DB::table('properties')
            ->whereNull('properties.deleted_at')
            ->whereBetween('properties.created_at', [$startDate, $endDate])

            ->selectRaw('
                COUNT(CASE WHEN properties.website_status = "available" AND properties.is_valid = 0 THEN 1 END) as without_permit_available,
                COUNT(CASE WHEN properties.website_status = "NA" AND properties.is_valid = 0 THEN 1 END) as without_permit_NA,
                COUNT(CASE WHEN properties.website_status = "rejected" AND properties.is_valid = 0 THEN 1 END) as without_permit_rejected,
                COUNT(CASE WHEN properties.website_status = "requested" AND properties.is_valid = 0 THEN 1 END) as without_permit_requested,

                COUNT(CASE WHEN properties.website_status = "available" AND properties.is_valid = 1 THEN 1 END) as with_permit_available,
                COUNT(CASE WHEN properties.website_status = "NA" AND properties.is_valid = 1 THEN 1 END) as with_permit_NA,
                COUNT(CASE WHEN properties.website_status = "rejected" AND properties.is_valid = 1 THEN 1 END) as with_permit_rejected,
                COUNT(CASE WHEN properties.website_status = "requested" AND properties.is_valid = 1 THEN 1 END) as with_permit_requested
            ')
            ->first();
    }

    public static function getCountsByPermitCategory($startDate, $endDate)
    {
        return DB::table('properties')
            ->whereNull('properties.deleted_at')
            ->whereBetween('properties.created_at', [$startDate, $endDate])

            ->selectRaw('
                COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 286 AND properties.is_valid = 0 THEN 1 END) as without_permit_ready,
                COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 287 AND properties.is_valid = 0 THEN 1 END) as without_permit_offplan,
                 COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 291 AND properties.is_valid = 0 THEN 1 END) as without_permit_offplan_resale,
                COUNT(CASE WHEN properties.category_id = 9  AND properties.is_valid = 0 THEN 1 END) as without_permit_rent,

                COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 286  AND properties.is_valid = 1 THEN 1 END) as with_permit_ready,
                COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 287  AND properties.is_valid = 1 THEN 1 END) as with_permit_offplan,
                  COUNT(CASE WHEN properties.category_id = 8 AND properties.completion_status_id = 291  AND properties.is_valid = 1 THEN 1 END) as with_permit_offplan_resale,
                COUNT(CASE WHEN properties.category_id = 9 AND properties.is_valid = 1 THEN 1 END) as with_permit_rent
            ')
            ->first();
    }
    public static function getCountsByCategory($startDate, $endDate)
    {

        return DB::table('properties')
            ->whereNull('properties.deleted_at')
            ->whereBetween('properties.created_at', [$startDate, $endDate])
            ->selectRaw('

                COUNT(CASE WHEN  website_status = "available" AND category_id = 8 AND completion_status_id = 286  THEN 1 END) as available_ready,
                COUNT(CASE WHEN website_status = "na" AND category_id = 8 AND completion_status_id = 286  THEN 1 END) as NA_ready,
                COUNT(CASE WHEN website_status = "rejected" AND category_id = 8 AND completion_status_id = 286  THEN 1 END) as rejected_ready,
                COUNT(CASE WHEN website_status = "requested" AND category_id = 8 AND completion_status_id = 286  THEN 1 END) as requested_ready,


                COUNT(CASE WHEN website_status = "available" AND category_id = 8 AND completion_status_id = 287  THEN 1 END) as available_offplan,
                COUNT(CASE WHEN website_status = "na" AND category_id = 8  AND completion_status_id = 287 THEN 1 END) as NA_offplan,
                COUNT(CASE WHEN website_status = "rejected" AND category_id = 8  AND completion_status_id = 287 THEN 1 END) as rejected_offplan,
                COUNT(CASE WHEN website_status = "requested" AND category_id = 8 AND completion_status_id = 287  THEN 1 END) as requested_offplan,

                COUNT(CASE WHEN website_status = "available" AND category_id = 8 AND completion_status_id = 291  THEN 1 END) as available_offplan_resale,
                COUNT(CASE WHEN website_status = "na" AND category_id = 8  AND completion_status_id = 291 THEN 1 END) as NA_offplan_resale,
                COUNT(CASE WHEN website_status = "rejected" AND category_id = 8  AND completion_status_id = 291 THEN 1 END) as rejected_offplan_resale,
                COUNT(CASE WHEN website_status = "requested" AND category_id = 8 AND completion_status_id = 291  THEN 1 END) as requested_offplan_resale,


                COUNT(CASE WHEN website_status = "available" AND category_id = 9   THEN 1 END) as available_rent,
                COUNT(CASE WHEN website_status = "na" AND category_id = 9   THEN 1 END) as NA_rent,
                COUNT(CASE WHEN website_status = "rejected" AND category_id = 9  THEN 1 END) as rejected_rent,
                COUNT(CASE WHEN website_status = "requested" AND category_id = 9  THEN 1 END) as requested_rent

        ')->first();

        // return [
        //     'ready' => DB::table('properties')
        //         ->whereNull('deleted_at')
        //         ->whereBetween('created_at', [$startDate, $endDate])
        //         ->where('category_id', 8)
        //         ->where('completion_status_id', 286)
        //         ->count(),

        //     'offplan' => DB::table('properties')
        //         ->whereNull('deleted_at')
        //         ->whereBetween('created_at', [$startDate, $endDate])
        //         ->where('category_id', 8)
        //         ->where('completion_status_id', 287)
        //         ->count(),

        //     'rent' => DB::table('properties')
        //         ->whereNull('deleted_at')
        //         ->whereBetween('created_at', [$startDate, $endDate])
        //         ->where('category_id', 9)
        //         ->count(),
        // ];
    }


    /**
     *
     * Filters
     */
    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);
        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }
    }
    public function scopeWebsiteStatus($query, $status)
    {
        return $query->where('website_status', $status);
    }

    public static function boot()
    {
        parent::boot();
        Property::observe(PropertyObserver::class);
    }
}
