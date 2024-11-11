<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'mobile_country_code',
        'mobile',
        'property_status',
        'property_type',
        'number_of_rooms',
        'min_price',
        'max_price',
    ];
}
