<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAmenity extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'amenity_id'
    ];
}
