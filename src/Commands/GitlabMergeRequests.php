<?php

namespace KoenHendriks\GitlabMergeRequests\Commands;

use Config;
use Illuminate\Console\Command;
use KoenHendriks\GitlabMergeRequests\Gitlab;

class GitlabMergeRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "gitlab:merge-requests" .
    "{days=7 : Amount of days to look back in time for merge requests.}" .
    "{--wip=no : Filter merge requests against their wip status. 'yes' to return only WIP merge requests, no to return non WIP merge requests}" .
    "{--state=merged : Return all merge requests or just those that are opened, 'closed', 'locked', or 'merged}'" .
    "{--order_by=created_at : Return requests ordered by 'created_at' or 'updated_at' fields.}" .
    "{--sort=desc : Return requests sorted in 'asc' or 'desc' order.}" .
    "{--labels=* : Return merge requests matching labels. 'None' lists all merge requests with no labels. 'Any' lists all merge requests with at least one label. Predefined names are case-insensitive.}" .
    "{--scope=all : Return merge requests for the given scope: 'created_by_me', 'assigned_to_me' or 'all'.}" .
    "{--milestone=any : Return merge requests for a specific milestone. 'None' returns merge requests with no milestone. 'Any' returns merge requests that have an assigned milestone.}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Gitlab Merge Requests from a period of time.';

    /**
     * The Gitlab instance we use from the package.
     */
    private $gitlab;

    /**
     * Create a new command instance.
     *
     * @param  Gitlab  $gitlab
     */
    public function __construct(Gitlab $gitlab)
    {
        parent::__construct();

        $this->gitlab = $gitlab;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle(): void
    {
        if (!is_numeric($this->argument('days'))) {
            $this->error('Please give a number in days to retrieve merqe requests from.');
        }

        $this->gitlab->setOptions($this->options());


        if (is_null(Config::get('gitlab-merge-requests.gitlab_api_key'))) {
            $this->error('ERROR');
            $this->error('Gitlab api key not set. Please set it in the .env file using GITLAB_API_KEY variable.');
            return;
        }

        $this->displayMergeRequests(
            $this->gitlab->getMergeRequests(
                $this->argument('days')
            )
        );

        return;
    }

    /**
     * Displays the merge request in the console.
     * @param  array  $mergeRequests
     */
    private function displayMergeRequests(array $mergeRequests): void
    {
        $count = 0;
        foreach ($mergeRequests as $mr) {
            $count++;
            $this->info($mr->title);
        }

        $this->line('');
        $this->info($count . ' total merge requests in the last ' . $this->argument('days') . ' days');
    }

}
