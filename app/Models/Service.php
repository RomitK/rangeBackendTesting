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

class Service extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText, HasSlug;
    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'icon',
        'formattedCreatedAt'
    ];
    /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'description'
    ];
    /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images', 'resize');
    }
    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl('icons', 'resize_icon');
    }
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize_icon')
            ->height(60)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('icons')
            ->nonQueued();

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
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    /**
     * FIND Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function childServies()
    {
        return $this->hasMany(Service::class, 'parent_id')->with('childServies');
    }

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
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
    public function scopeMainService($query)
    {
        return $query->where('is_parent', true);
    }

}
