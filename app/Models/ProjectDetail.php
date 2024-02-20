<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;
    protected $fillable = ['key','value'];
    
    public function paymentPlans()
    {
        return $this->morphMany(MetaDetail::class, 'detailable');
    }
}
