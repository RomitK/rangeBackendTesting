<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable =['tag_category_id'];

    public function tagable(): MorphTo
    {
        return $this->morphTo();
    }
    public function tagCategory()
    {
        return $this->belong(TagCategory::Class);
    }
}
