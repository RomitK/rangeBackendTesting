<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Career extends Model
{
    use HasFactory, SoftDeletes, HasRichText, HasSlug;

    const  JOB_TYPE = [
        'full_time' => 'Full Time',
        'part_time' => 'Part Time'
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
     * The richtext attributes
     *
     * @var array
     */
    protected $richTextFields = [
        'key_responsibilities',
        'requirements',
        'description',
        'assistance',
        'benefits'
    ];
    /**
     * The attributes that should be append with arrays.
     *
     * @var array
     */
    protected $appends = [
        'formattedCreatedAt'
    ];
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('position')
            ->saveSlugsTo('slug');
    }
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
    public function applicants()
    {
        return $this->hasMany(CareerApplicant::class);
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
        return $query->where('status',  config('constants.deactive'));
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public static function getCountsByDate($startDate, $endDate)
    {
        return DB::table('careers')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public static function getCountsByStatus($startDate, $endDate)
    {
        return DB::table('careers')
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
