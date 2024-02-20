<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagegallery extends Model
{
    use HasFactory;
    
    protected $primaryKey = "id";
    protected $fillable = [
        'property_id',
        'image',
        'category'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
