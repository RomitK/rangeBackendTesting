<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Award extends Model implements HasMedia
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
        'trophy',
        'gallery',
        'badge',
        'formattedCreatedAt'
    ];
    /**
     * SET Attributes
     */
    /**
     * GET Attributes
     */
    public function getTrophyAttribute()
    {
        return $this->getFirstMediaUrl('trophies', 'resize_trophies');
    }
    public function getGalleryAttribute()
    {
        $gallery = array();
        foreach($this->getMedia('gallery') as $image){
            if($image->hasGeneratedConversion('resize_gallery')){
                array_push($gallery, ['id'=> $image->id, 'path'=>$image->getUrl('resize_gallery')]);
            }else{
                array_push($gallery, ['id'=> $image->id, 'path'=>$image->getUrl()]);
            }
        }

       return $gallery;
    }
    public function getBadgeAttribute()
    {
        return $this->getFirstMediaUrl('badges', 'resize_badges');
    }

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize_trophies')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('trophies')
            ->nonQueued();

        $this->addMediaConversion('resize_badges')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('badges')
            ->nonQueued();

        $this->addMediaConversion('resize_gallery')
            ->format(Manipulations::FORMAT_WEBP)
            ->height(220)
            ->performOnCollections('gallery')
            ->nonQueued();
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
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
}
