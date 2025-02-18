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

class Guide extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
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

    public function getFeatureImageAttribute()
    {
        return $this->getFirstMediaUrl('featureImages', 'resize');
    }
    public function getSliderImageAttribute()
    {
        return $this->getFirstMediaUrl('sliderImages', 'resize');
    }
    public function getGuideFileAttribute()
    {
        return $this->getFirstMediaUrl('guides');
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('logos')
            ->nonQueued();

        $this->addMediaConversion('resize')
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
    public function tags()
    {
        return $this->morphMany(Tag::class, 'tagable');
    }
    /**
     * FIND local scope
     */
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

    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('guides')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('guides')
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
        return DB::table('guides')
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
}
