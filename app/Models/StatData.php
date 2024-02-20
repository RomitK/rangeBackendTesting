<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatData extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable =['key', 'value'];
    /**
     * FIND Relationship
     */
    public function stat()
    {
        return $this->belongsTo(Stat::class);
    }
}
