<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\{
    Project,
    Community,
    Property,
    Developer,
    Article,
    Career,
    Guide,
    Agent
};

class WebsiteStateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $collection = Article::active()->approved();


        $medias = clone $collection;
        $news = clone $collection;
        $blogs = clone $collection;
        $awards = clone $collection;
        $celebrations = clone $collection;

        $propertiesCollection = Property::approved()->active();
        $propertiesCount = clone $propertiesCollection;
        $ready = clone $propertiesCollection;
        $offplan = clone $propertiesCollection;
        $rentProperties = clone $propertiesCollection;

        $data = [
            'allMedias' => $medias->count(),
            'types' => [
                'News' => $news->news()->count(),
                'Blogs' => $blogs->blogs()->count(),
                'Awards' => $awards->awards()->count(),
                'Celebrations' => $celebrations->celebrations()->count(),
            ],
            'teams' => Agent::active()->where('is_management', 0)->count(),
            'careers' => Career::active()->count(),
            'guides' => Guide::active()->approved()->count(),
            'communities' => Community::active()->approved()->count(),
            'developers' => Developer::active()->approved()->count(),
            'projects' => Project::approved()->active()->mainProject()->count(),
            'properties' => $propertiesCount->count(),
            'propertiesTypes' => [
                'Ready' => $ready->where('completion_status_id', 286)->count(),
                'Offplan' => $offplan->where('completion_status_id', 287)->count(),
                'Rent' => $rentProperties->rent()->count()
            ]
        ];

        Log::info($data);

        $recipients = [
            ['name' => 'Aqsa', 'email' => 'aqsa@xpertise.ae'],
            // ['name' => 'Nitin Chopra', 'email' => 'nitin@range.ae'],
            // ['name' => 'Lester Verma', 'email' => 'lester@range.ae'],
            // ['name' => 'Romit Kumar', 'email' => 'romit@range.ae'],
            // ['name' => 'Safeena Ahmad', 'email' => 'safeeena@xpertise.ae'],
        ];

        foreach ($recipients as $recipient) {
            $name = $recipient['name'];
            $email = $recipient['email'];

            $data['userName'] = $name; // Change userName for each recipient

            Mail::send('mails.websiteStatReport', ['data' => $data], function ($message) use ($email, $name) {
                $message->to($email, $name)->subject('Website Stat Report');
            });
        }
    }
}
