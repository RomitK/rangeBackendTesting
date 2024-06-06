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

class Community extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;
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
        // 'short_description'
    ];
    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'websiteStatus',
        'mainImage',
        // 'listMainImage'
        'clusterPlan',
        //'imageGallery',
        // 'video',
        //'formattedCreatedAt',

    ];
    public function getWebsiteStatusAttribute()
    {
        if ($this->status == config('constants.active') && $this->is_approved == config('constants.approved')) {
            return config('constants.Available');
        } elseif ($this->status == config('constants.Inactive') && $this->is_approved == config('constants.approved')) {
            return config('constants.NA');
        } elseif ($this->is_approved == config('constants.rejected')) {
            return config('constants.Rejected');
        } elseif ($this->is_approved == config('constants.requested')) {
            return config('constants.Requested');
        }
    }
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
    /**
     * GET Attributes
     */
    public function getMainImageAttribute()
    {
        if (url_exists($this->getFirstMediaUrl('mainImages', 'resize'))) {
            return $this->getFirstMediaUrl('mainImages', 'resize');
        }
        return false;
        //return $this->getFirstMediaUrl('mainImages', 'resize');
    }

    public function getListMainImageAttribute()
    {
        if (url_exists($this->getFirstMediaUrl('listMainImages', 'resize'))) {
            return $this->getFirstMediaUrl('listMainImages', 'resize');
        }
        return false;
        //return $this->getFirstMediaUrl('mainImages', 'resize');
    }

    public function getClusterPlanAttribute()
    {
        return $this->getFirstMediaUrl('clusterPlans', 'resize');
    }
    // public function getImageGalleryAttribute()
    // {
    //     $subImages = array();
    //     foreach($this->getMedia('imageGalleries') as $image){
    //         if($image->hasGeneratedConversion('resize_images')){
    //             array_push($subImages, ['id'=> $image->id, 'path'=>$image->getUrl('resize_images')]);
    //         }else{
    //             array_push($subImages, ['id'=> $image->id, 'path'=>$image->getUrl()]);
    //         }
    //     }
    //   return $subImages;
    // }


    public function getImageGalleryAttribute()
    {
        $gallery = array();
        foreach ($this->getMedia('imageGalleries')->sortBy(function ($mediaItem, $key) {
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

    public function getvideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            //->height(600)
            ->performOnCollections('mainImages')
            ->nonQueued();

        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            // ->height(400)
            ->performOnCollections('listMainImages')
            ->nonQueued();

        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            //->width(800)
            ->height(1000)
            ->performOnCollections('clusterPlans')
            ->nonQueued();

        $this->addMediaConversion('resize_images')
            ->format(Manipulations::FORMAT_WEBP)
            //->height(600)
            ->performOnCollections('imageGalleries')
            ->nonQueued();
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
    public function stats()
    {
        return $this->morphMany(Stat::class, 'statable');
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'community_amenities', 'community_id', 'amenity_id');
    }
    public function highlights()
    {
        return $this->belongsToMany(Highlight::class, 'community_highlights', 'community_id', 'highlight_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'community_categories', 'community_id', 'category_id');
    }
    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'community_developers', 'community_id', 'developer_id');
    }
    public function communities()
    {
        return $this->belongsToMany(Developer::class, 'agent_communities', 'community_id', 'agent_id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function properties()
    {
        return $this->projects()->withCount('properties');
    }
    public function tags()
    {
        return $this->morphMany(Tag::class, 'tagable');
    }
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

    // public function properties()
    // {
    //     return $this->hasMany(Property::class);
    // }
    public function subCommunities()
    {
        return $this->hasMany(Subcommunity::class);
    }
    // public function communities()
    // {
    //     return $this->belongsTo(Community::class, 'community_id', 'id');
    // }
    public function communityDevelopers()
    {
        return $this->belongsToMany(Developer::class, 'community_developers', 'community_id', 'developer_id');
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
    public function scopeHome($query)
    {
        return $query->where('display_on_home', 1);
    }
    public function scopeMain($query)
    {
        return $query->where('parent_id', null);
    }

    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('communities')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('communities')
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

    public static function getCountsByApprovalStatus($startDate, $endDate)
    {
        return DB::table('communities')
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
    public function scopeWebsiteStatus($query, $status)
    {
        if ($status == config('constants.Available')) {
            $query->active()->approved();
        } elseif ($status == config('constants.NA')) {
            $query->deactive()->approved();
        } elseif ($status == config('constants.Requested')) {
            $query->requested();
        } elseif ($status == config('constants.Rejected')) {
            $query->rejected();
        }
    }
    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);
        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }
    }
}
