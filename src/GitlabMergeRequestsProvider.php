<?php

namespace KoenHendriks\GitlabMergeRequests;

use Illuminate\Support\ServiceProvider;
use KoenHendriks\GitlabMergeRequests\Commands\GitlabMergeRequests;

class GitlabMergeRequestsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gitlab-merge-requests.php', 'gitlab-merge-requests');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration
        $this->publishes([
            __DIR__ . '/../config/gitlab-merge-requests.php' => config_path('gitlab-merge-request.php'),
        ]);

        // Command for this package.
        if ($this->app->runningInConsole()) {
            $this->commands([
                GitlabMergeRequests::class
            ]);
        }
    }
}
