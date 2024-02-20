<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;
    protected $fillable =['name'];

    /**
     * FIND Relationship
     */
    public function statable(): MorphTo
    {
        return $this->morphTo();
    }
    public function values()
    {
        return $this->hasMany(StatData::class);
    }
}
