<?php

namespace KoenHendriks\GitlabMergeRequests\Commands;

use Illuminate\Console\Command;

class GitlabMergeRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitlab:merge-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Gitlab Merge Requests from a period of time.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}
