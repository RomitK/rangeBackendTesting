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
use Illuminate\Support\Str;

class Agent extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'license_number'
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
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'qr',
        'designationUrl',
        'image',
        'additionalImage',
        'video',
        'formattedCreatedAt',
        'firstName'
    ];
    /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'message'
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
    public function getDesignationUrlAttribute()
    {
        return Str::slug($this->designation);
    }
    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }
    public function getQrAttribute()
    {
        return $this->getFirstMediaUrl('QRs');
    }
    public function getImageAttribute()
    {
        // return $this->getFirstMediaUrl('images', 'resize');

        if ($this->getFirstMediaUrl('images', 'resize')) {
            return $this->getFirstMediaUrl('images', 'resize');
        } else {
            return asset('frontend/assets/images/no-user.webp');
        }
    }

    public function getAdditionalImageAttribute()
    {
        // return $this->getFirstMediaUrl('images', 'resize');

        if ($this->getFirstMediaUrl('additional_images', 'resize')) {
            return $this->getFirstMediaUrl('additional_images', 'resize');
        } else {
            return asset('frontend/assets/images/no-user.webp');
        }
    }

    public function getFirstNameAttribute($value)
    {
        return strtok($this->name, " ");
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            //->height(300)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('images')
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
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'agent_languages', 'agent_id', 'language_id');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'agent_projects', 'agent_id', 'project_id');
    }
    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'agent_developers', 'agent_id', 'developer_id');
    }
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'agent_communities', 'agent_id', 'community_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'agent_services', 'agent_id', 'service_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
    public function saleProperties()
    {
        return $this->properties()->where('category_id', config('constants.categories')["Sale"]);
    }
    public function resaleProperties()
    {
        return $this->properties()->where('category_id', config('constants.categories')["Resale"]);
    }
    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('agents')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('agents')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
            COUNT(CASE WHEN status = "active" AND is_approved = "approved" THEN 1 END) as available,
            COUNT(CASE WHEN status = "inactive" AND is_approved = "approved" THEN 1 END) as NP,
            COUNT(CASE WHEN is_approved = "rejected" THEN 1 END) as rejected,
            COUNT(CASE WHEN is_approved = "requested" THEN 1 END) as requested
            ')
            ->first();
    }

    public static function getCountsByApprovalStatus($startDate, $endDate)
    {
        return DB::table('agents')
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
     * FIND local scope
     */
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
    public function scopeMangement($query, $status)
    {
        return $query->where('is_management', 1);
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
}
