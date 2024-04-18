<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    public $afterCommit = true;
    /**
     * Handle the Testimonial "updated" event.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return void
     */
    public function updated(Testimonial $testimonial)
    {
        $attributesToCheck = ['feedback', 'client_name', 'rating', 'status', 'deleted_at'];
        if ($testimonial->isDirty($attributesToCheck)) {
            Cache::forget('homeTestimonials');
        }
    }
}
