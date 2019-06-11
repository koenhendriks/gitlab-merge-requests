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
    protected $signature = 'gitlab:merge-requests {from=7}';

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
    public function handle()
    {
        $days = $this->argument('from');

        if (!is_numeric($days)) {
            $this->error('Please give a number in days to retreive merqe requests from.');
            return;
        }

        if (is_null(Config::get('gitlab-merge-requests.gitlab_api_key'))) {
            $this->error('ERROR');
            $this->error('Gitlab api key not set. Please set it in the .env file using GITLAB_API_KEY variable.');
            return;
        }

        $mrs = $this->gitlab->getMergeRequests($days);
        $count = 0;
        foreach ($mrs as $mr) {
            $count++;
            $this->info($mr->title);
        }

        $this->line('');
        $this->info($count . ' total merge requests in the last ' . $days . ' days');

        return;
    }

}
