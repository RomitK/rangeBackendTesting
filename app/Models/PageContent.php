<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;

class PageContent extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasRichText;
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
        'video',
        'image',
        'Gallery',
        'formattedCreatedAt'
    ];
        /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'less_description',
        'more_description',
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
    public function getGoldenVisaAttribute()
    {
        return $this->getFirstMediaUrl('goldenVisa');
    }
    public function getBrochureAttribute()
    {
        return $this->getFirstMediaUrl('brochure');
    }
    public function getBuyerGuideAttribute()
    {
        return $this->getFirstMediaUrl('buyerGuide');
    }
    
    public function getSellerGuideAttribute()
    {
        return $this->getFirstMediaUrl('sellerGuide');
    }
    
    public function getPropertiesGuideAttribute()
    {
        return $this->getFirstMediaUrl('propertiesGuide');
    }
    
    public function getVideoAttribute()
    {
        return $this->getFirstMediaUrl('videos');
    }
    public function getGalleryAttribute()
    {
        $Gallery = array();
        foreach($this->getMedia('Gallery') as $image){
            if($image->hasGeneratedConversion('resize_gallers')){
                array_push($Gallery, ['id'=>$image->id, 'path'=>$image->getUrl('resize_gallers')]);
            }else{
                array_push($Gallery, ['id'=>$image->id, 'path'=>$image->getUrl()]);
            }
        }
       return $Gallery;
    }
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resize')
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('images')
            ->nonQueued();
        
        $this->addMediaConversion('resize_gallers')
            ->height(1000)
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('Gallery')
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
    /**
    * FIND local scope
    */
    public function scopeHome($query)
    {
        return $query->where('page_name', config('constants.home.name'));
    }
    public function scopeCeo($query)
    {
        return $query->where('page_name', config('constants.ceo.name'));
    }
    public function scopeAbout($query)
    {
        return $query->where('page_name', config('constants.about.name'));
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
    public function scopeWherePageName($query, $page_name)
    {
        return $query->where('page_name', $page_name);
    }
}

