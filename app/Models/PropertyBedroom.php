<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PropertyBedroom extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = "id";
    protected $fillable = [
        'property_id',
        'bedroom'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

}
