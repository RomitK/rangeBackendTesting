<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Carbon\Carbon;

class Highlight extends Model implements HasMedia
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
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'formattedCreatedAt'
    ];
    /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images','resize');
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize')
            ->height(60)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('images')
            ->nonQueued();
    }
    /**
     * FIND Relationship
     */
    public function approval(){
        return $this->belongsTo(User::class,'approval_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_amenities', 'amenity_id', 'property_id');
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
    public function scopeApproved($query)
    {
        return $query->where('is_approved', config('constants.approved'));
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
