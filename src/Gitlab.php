<?php

namespace KoenHendriks\GitlabMergeRequests;

use Config;

/**
 * Class GitlabMergeRequests
 * @package KoenHendriks\GitlabMergeRequests
 */
class Gitlab
{
    /**
     * @var string API key for use in Gitlab requests.
     */
    private $key;

    /**
     * Gitlab constructor.
     */
    public function __construct()
    {
        $this->key = Config::get('gitlab-merge-requests.gitlab_api_key');
    }

    public function hi()
    {
        return 'key: ' . $this->key;
    }
}
