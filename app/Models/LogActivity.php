<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LogActivity extends Model
{
   protected $table = 'activity_log';
   protected $fillable = ['log_name'];

   protected $appends = [
      'formattedCreatedAt'
   ];

   public function getFormattedCreatedAtAttribute($value)
   {
      return Carbon::parse($this->created_at)->timezone('Asia/Dubai')->format('d m Y H:i:s');
   }

   public function users()
   {
      return $this->hasMany(User::class, 'id', 'causer_id');
   }
   public function user()
   {
      return $this->hasOne(User::class, 'id', 'causer_id');
   }
}
