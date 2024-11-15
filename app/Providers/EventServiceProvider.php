<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Community;
use App\Models\Developer;
use App\Models\Property;
use App\Models\Testimonial;
use App\Models\Project;
use App\Observers\CommunityObserver;
use App\Observers\DeveloperObserver;
use App\Observers\PropertyObserver;
use App\Observers\TestimonialObserver;
use App\Observers\ProjectObserver;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use App\Listeners\SendJobStartedNotification;
use App\Listeners\SendJobCompletedNotification;
use App\Listeners\SendJobFailedNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        JobProcessing::class => [
            SendJobStartedNotification::class,
        ],
        JobProcessed::class => [
            SendJobCompletedNotification::class,
        ],
        JobFailed::class => [
            SendJobFailedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
        Community::observe(CommunityObserver::class);
        Developer::observe(DeveloperObserver::class);
        Testimonial::observe(TestimonialObserver::class);
        Project::observe(ProjectObserver::class);
        Property::observe(PropertyObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
