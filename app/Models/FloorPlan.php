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

class FloorPlan extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia,HasSlug;
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
     *
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'floorPlanFile',
        'mainImage',
        'formattedCreatedAt'
    ];
     /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
     /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    
    public function getFloorPlanFileAttribute()
    {
        return $this->getFirstMediaUrl('files');
    }
    public function getMainImageAttribute()
    {
        return $this->getFirstMediaUrl('images', 'resize');
    }
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('images')
            ->nonQueued();
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
     /**
     * FIND Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
    public function subCommunity()
    {
        return $this->belongsTo(Subcommunity::class);
    }
    public function subFloorPlans()
    {
        return $this->hasMany(SubFloorPlan::class);
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
    protected static function booted(): void
    {
        parent::boot();
        static::retrieved(function($floorPlan) {});
        //When a new model is saved for the first time,
        static::creating(function($floorPlan) {});
        static::created(function($floorPlan) {});
        //when a model is created or updated - even if the model's attributes have not been changed.
        static::saving(function($floorPlan) {});
        static::saved(function($floorPlan) {});
        // when an existing model is modified and the save method is called.
        static::updating(function($floorPlan) {});
        static::updated(function($floorPlan) {});
        static::deleting(function($floorPlan) {});
    }
}
