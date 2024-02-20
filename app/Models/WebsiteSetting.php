<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;
use Auth;
class WebsiteSetting extends Model implements HasMedia
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
        'logo',
        'formattedCreatedAt'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('logos');
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('d m Y');
    }
    public static function setSetting($key, $value, $userId= null)
    {
        $old = self::where('key',$key)->first();

        if ($old) {
            $old->value = $value;
            $old->save();
            return;
        }

        $set = new WebsiteSetting();
        $set->key = $key;
        $set->value = $value;
        if($userId){
            $set->user_id = $userId;
        }else{
            $set->user_id = Auth()->user()->id;
        }

        $set->save();
    }

    public static function getSetting($key)
    {
        $setting = static::where('key',$key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return null;
        }
    }
    public static function getLogo()
    {
        return self::where('key', 'logo')->first()->getFirstMediaUrl('logos');
    }
    public static function getFavicon()
    {
        return self::where('key', 'favicon')->first()->getFirstMediaUrl('favicons');
    }
    
    public static function getFooterLogo()
    {
        return self::where('key', 'footer_logo')->first()->getFirstMediaUrl('footer_logos');
    }
    
}
