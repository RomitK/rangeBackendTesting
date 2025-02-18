<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Developer extends Model implements HasMedia
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
        'long_description',
        'short_description'
    ];
    /**
     *
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'logo',
        'video',
        'image',
        'gallery',

        'formattedCreatedAt',
        'formattedUpdatedAt'
    ];

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

    public function getGalleryAttribute()
    {
        $gallery = array();
        foreach ($this->getMedia('gallery')->sortBy(function ($mediaItem, $key) {
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
    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logos', 'resize');
    }
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images', 'resize_images');
    }
    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
    public function getFormattedUpdatedAtAttribute($value)
    {
        return Carbon::parse($this->updated_at)->format('d m Y');
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            //->height(427)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('logos')
            ->nonQueued();

        $this->addMediaConversion('resize_images')
            // ->height(300)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('images')
            ->nonQueued();

        $this->addMediaConversion('resize_gallery')
            ->format(Manipulations::FORMAT_WEBP)
            //->height(600)
            // ->watermark(public_path('frontend/assets/images/favicon.png'))
            // ->watermarkPosition(Manipulations::POSITION_CENTER)
            // ->watermarkPadding(10)
            // ->watermarkHeight(70, Manipulations::UNIT_PERCENT)
            // ->watermarkOpacity(30)
            ->performOnCollections('gallery')
            ->nonQueued();
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
    public function details()
    {
        return $this->morphMany(MetaDetail::class, 'detailable');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function properties()
    {
        return $this->projects()->withCount('properties');
    }

    // public function properties()
    // {
    //     return $this->hasMany(Property::class, 'developer_id', 'id');
    // }

    public function tags()
    {
        return $this->morphMany(Tag::class, 'tagable');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_developers', 'developer_id', 'community_id');
    }
    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'agent_developers', 'developer_id', 'agent_id');
    }
    public function communityDevelopers()
    {
        return $this->belongsToMany(Community::class, 'community_developers', 'developer_id', 'community_id');
    }
    public function awards()
    {
        return $this->hasMany(Award::class);
    }
    /**
     * FIND local scope
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', config('constants.approved'));
    }
    public function scopeRejected($query)
    {
        return $query->where('is_approved', config('constants.rejected'));
    }
    public function scopeRequested($query)
    {
        return $query->where('is_approved', config('constants.requested'));
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

    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('developers')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('developers')
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
        return DB::table('developers')
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
        return DB::table('developers')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN is_approved = "requested" THEN 1 END) as requested,
                COUNT(CASE WHEN is_approved = "approved" THEN 1 END) as approved,
                COUNT(CASE WHEN is_approved = "rejected" THEN 1 END) as rejected
            ')
            ->first();
    }
    public function logActivity()
    {
        return $this->hasMany(LogActivity::class, 'subject_id', 'id')->orderBy('id', 'desc');
    }

    /**
     *
     * Filters
     */
    public function scopeWebsiteStatus($query, $status)
    {
        return $query->where('website_status', $status);
    }
    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);
        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }
    }
}
