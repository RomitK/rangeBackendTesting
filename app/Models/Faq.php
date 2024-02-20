<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;

class Faq extends Model implements HasMedia
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
        'formattedCreatedAt'
    ];
        /**
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'long_answer',
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
        return $query->where('page_name', config('constants.Home'));
    }
    public function scopeAbout($query)
    {
        return $query->where('page_name', config('constants.About'));
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


