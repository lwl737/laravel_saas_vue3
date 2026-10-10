<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\PodcastProcessed;
use App\Events\Podcast1Processed;
use App\Listeners\SendPodcastNotification;
use Illuminate\Support\Facades\Event;
// use App\Events\PodcastProcessed;
class Test1ServiceProvider extends ServiceProvider
{
 /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            PodcastProcessed::class,
            SendPodcastNotification::class,
        );
        // Event::listen(
        //     Podcast1Processed::class,
        //     SendPodcastNotification::class,
        // );
    }

}
