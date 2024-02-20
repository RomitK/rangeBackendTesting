<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class MetaDetail extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'key', 'value', 'value_1'];

    public function detailable(): MorphTo
    {
        return $this->morphTo();
    }
    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'icon',
    ];
    /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl('icons','resize' );
    }
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('icons')
            ->nonQueued();
        
    }
}
