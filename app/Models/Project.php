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
use Illuminate\Support\Facades\DB;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;
    protected $fillable = ['qr_link'];

    // public $timestamps = false; // Set to false to disable automatic timestamping
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
        'short_description',
        'long_description'
    ];

    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'qr',
        'clusterPlan',
        'mainImage',
        'video',
        'saleOffer',
        'interiorGallery',
        'exteriorGallery',
        'factsheet',
        'brochure',
        'paymentPlan',
        'formattedCreatedAt',
        'formattedUpdatedAt'
    ];
    /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    // public function getWebsiteStatusAttribute()
    // {
    //     if ($this->status == config('constants.active') && $this->is_approved == config('constants.approved')) {
    //         return config('constants.Available');
    //     } elseif ($this->status == config('constants.Inactive') && $this->is_approved == config('constants.approved')) {
    //         return config('constants.NA');
    //     } elseif ($this->is_approved == config('constants.rejected')) {
    //         return config('constants.Rejected');
    //     } elseif ($this->is_approved == config('constants.requested')) {
    //         return config('constants.Requested');
    //     }
    // }

    public static function getNextReferenceNumber($value)
    {
        // Get the last created order
        $lastOrder = Project::where('reference_number', 'LIKE', $value . '_%')
            ->orderBy('id', 'desc')
            ->first();


        if (!$lastOrder) {
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            $number = explode("_", $lastOrder->reference_number);
            $number = $number[2];
        }
        // If we have ORD000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %06d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return sprintf('%d', intval($number) + 1);
    }
    public function getMainImageAttribute()
    {

        if (url_exists($this->getFirstMediaUrl('mainImages', 'resize'))) {
            return $this->getFirstMediaUrl('mainImages', 'resize');
        } else {
            return asset('frontend/assets/images/no-image.webp');
        }

        //return $this->getFirstMediaUrl('mainImages', 'resize');
    }
    public function getQrAttribute()
    {
        return $this->getFirstMediaUrl('qrs', 'resize');
    }

    public function getClusterPlanAttribute()
    {
        return $this->getFirstMediaUrl('clusterPlans', 'resize');
    }
    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }

    public function getSaleOfferAttribute()
    {
        return $this->getFirstMediaUrl('saleOffers', 'resize');
    }
    public function getInteriorGalleryAttribute()
    {
        //     $interiorGallery = array();
        //     foreach($this->getMedia('interiorGallery') as $image){
        //         if($image->hasGeneratedConversion('resize_images')){
        //             array_push($interiorGallery, ['id'=> $image->id, 'path'=>$image->getUrl('resize_images')]);
        //         }else{
        //             array_push($interiorGallery, ['id'=> $image->id, 'path'=>$image->getUrl()]);
        //         }
        //     }
        //   return $interiorGallery;

        $gallery = array();
        foreach ($this->getMedia('interiorGallery')->sortBy(function ($mediaItem, $key) {
            $order = $mediaItem->getCustomProperty('order');
            return $order ?? PHP_INT_MAX;
        }) as $image) {
            if ($image->hasGeneratedConversion('resize_gallery')) {
                array_push(
                    $gallery,
                    [
                        'id' => $image->id,
                        'path' => $image->getUrl('resize_gallery'),
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
    public function getExteriorGalleryAttribute()
    {
        //     $exteriorGallery = array();
        //     foreach($this->getMedia('exteriorGallery') as $image){
        //         if($image->hasGeneratedConversion('resize_images')){
        //             array_push($exteriorGallery, ['id'=> $image->id, 'path'=>$image->getUrl('resize_images')]);
        //         }else{
        //             array_push($exteriorGallery, ['id'=> $image->id, 'path'=>$image->getUrl()]);
        //         }
        //     }
        //   return $exteriorGallery;

        $gallery = array();
        foreach ($this->getMedia('exteriorGallery')->sortBy(function ($mediaItem, $key) {
            return $mediaItem->getCustomProperty('order');
        }) as $image) {
            if ($image->hasGeneratedConversion('resize_gallery')) {
                array_push(
                    $gallery,
                    [
                        'id' => $image->id,
                        'path' => $image->getUrl('resize_gallery'),
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

    public function getFloorPlanAttribute()
    {
        $floorPlans = array();
        foreach ($this->getMedia('floorPlans') as $image) {
            if ($image->hasGeneratedConversion('resize_images')) {
                array_push($floorPlans, ['id' => $image->id, 'path' => $image->getUrl('resize_images')]);
            } else {
                array_push($floorPlans, ['id' => $image->id, 'path' => $image->getUrl()]);
            }
        }
        return $floorPlans;

        // return $this->getFirstMediaUrl('floorPlans');
    }

    public function registerMediaConversions(Media $media = null): void
    {

        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('qrs')
            ->nonQueued();


        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            //->width(800)
            ->height(300)
            ->performOnCollections('mainImages')
            ->nonQueued();

        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            //->width(800)
            ->height(1000)
            ->performOnCollections('clusterPlans')
            ->nonQueued();



        $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            //->width(400)
            ->height(600)
            ->performOnCollections('interiorGallery')
            ->nonQueued();

        $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('exteriorGallery')
            //->width(800)
            ->height(800)
            ->nonQueued();

        $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('floorPlans')
            //->width(600)
            ->height(400)
            ->nonQueued();
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    public function getPaymentPlanAttribute()
    {
        return $this->getFirstMediaUrl('paymentPlans');
    }
    public function getFactSheetAttribute()
    {
        return $this->getFirstMediaUrl('factsheets');
    }
    public function getBrochureAttribute()
    {
        return $this->getFirstMediaUrl('brochures');
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
    public function completionStatus()
    {
        return $this->belongsTo(CompletionStatus::class, 'completion_status_id', 'id');
    }
    public function stats()
    {
        return $this->morphMany(Stat::class, 'statable');
    }
    public function tags()
    {
        return $this->morphMany(Tag::class, 'tagable');
    }
    public function properties()
    {
        return $this->hasMany(Property::class, 'project_id', 'id');
    }

    public function subProjects()
    {
        return $this->hasMany(Project::class, 'parent_project_id')->with('subProjects');
    }

    public function parentProject()
    {
        return $this->belongsTo(Project::class, 'parent_project_id');
    }
    public function approval()
    {
        return $this->belongsTo(User::class, 'approval_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id', 'id');
    }
    public function mainCommunity()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
    public function subCommunity()
    {
        return $this->belongsTo(Community::class, 'sub_community_id', 'id');
    }
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id', 'id');
        // return $this->belongsToMany(Accommodation::class, 'project_accommodations', 'project_id' , 'accommodation_id');
    }
    public function highlights()
    {
        return $this->belongsToMany(Highlight::class, 'project_highlights', 'project_id', 'highlight_id');
    }

    public function amenities()
    {

        return $this->belongsToMany(Amenity::class, 'project_amenities', 'project_id', 'amenity_id');
    }
    public function highlighted_amenities()
    {
        return $this->amenities()->where('highlighted', '=', 1);
    }
    public function unhighlighted_amenities()
    {
        return $this->amenities()->where('highlighted', '=', 0);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agent_projects', 'project_id', 'agent_id');
    }
    public function projectBedrooms()
    {
        return $this->hasMany(ProjectBedroom::class, 'project_id', 'id');
    }
    public function floorPlan()
    {
        return $this->hasOne(FloorPlan::class, 'project_id', 'id');
    }
    public function details()
    {
        return $this->hasMany(ProjectDetail::class, 'project_id', 'id');
    }
    public function mPaymentPlans()
    {
        return $this->hasMany(ProjectDetail::class, 'project_id', 'id')->where('key', 'payment',);
    }
    public function paymentPlans()
    {
        return $this->morphMany(MetaDetail::class, 'detailable');
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
    public function scopeDeactive($query)
    {
        return $query->where('status',  config('constants.Inactive'));
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeMainProject($query)
    {
        return $query->where('is_parent_project', true);
    }
    public function scopeHome($query)
    {
        return $query->where('is_display_home', 1);
    }
    public function scopeNewLunch($query)
    {
        return $query->where('is_new_launch', '1');
    }
    public function logActivity()
    {
        return $this->hasMany(LogActivity::class, 'subject_id', 'id')->orderBy('id', 'desc');
    }

    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('projects')
            ->where('is_parent_project', true)
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('projects')
            ->where('is_parent_project', true)
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
        return DB::table('projects')
            ->where('is_parent_project', true)
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


    public static function getCountsByPermitNumber($startDate, $endDate)
    {
        return DB::table('projects')
            ->where('is_parent_project', true)
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
            
            COUNT(CASE WHEN status = "active" AND is_approved = "approved" AND permit_number IS NULL AND (qr_link IS NULL OR qr_link ="")  THEN 1 END) as without_permit_available,
            COUNT(CASE WHEN status = "inactive" AND is_approved = "approved" AND permit_number IS NULL AND (qr_link IS NULL OR qr_link = "") THEN 1 END) as without_permit_NA,
            COUNT(CASE WHEN is_approved = "rejected" AND permit_number IS NULL  AND (qr_link IS NULL OR qr_link ="")  THEN 1 END) as without_permit_rejected,
            COUNT(CASE WHEN is_approved = "requested" AND permit_number IS NULL AND (qr_link IS NULL OR qr_link ="") THEN 1 END) as without_permit_requested,


            COUNT(CASE WHEN status = "active" AND is_approved = "approved" AND permit_number IS NOT NULL AND qr_link != "" THEN 1 END) as with_permit_available,
            COUNT(CASE WHEN status = "inactive" AND is_approved = "approved" AND permit_number IS NOT NULL AND qr_link != "" THEN 1 END) as with_permit_NA,
            COUNT(CASE WHEN is_approved = "rejected" AND permit_number IS NOT NULL AND qr_link != "" THEN 1 END) as with_permit_rejected,
            COUNT(CASE WHEN is_approved = "requested" AND permit_number IS NOT NULL AND qr_link != "" THEN 1 END) as with_permit_requested


        ')->first();
    }


    public static function getCountsByApprovalStatus($startDate, $endDate)
    {
        return DB::table('projects')
            ->where('is_parent_project', true)
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN is_approved = "requested" THEN 1 END) as requested,
                COUNT(CASE WHEN is_approved = "approved" THEN 1 END) as approved,
                COUNT(CASE WHEN is_approved = "rejected" THEN 1 END) as rejected
            ')
            ->first();
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
}
