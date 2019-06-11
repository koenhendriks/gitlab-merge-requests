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
     * @var string API endpoint to the Gitlab instance.
     */
    private $endpoint;

    /**
     * Gitlab constructor.
     */
    public function __construct()
    {
        $this->key = Config::get('gitlab-merge-requests.gitlab_api_key');
        $this->setEndpoint();
    }

    public function hi()
    {
        return 'endpoint: ' . $this->endpoint;
    }

    /**
     * Create the endpoint of the Gitlab instance from the config of the package.
     */
    private function setEndpoint()
    {
        // Right trim the last '/' so even if the user sets this in the config we still get the correct url
        $url = rtrim(Config::get('gitlab-merge-requests.gitlab_url'), '/');
        $url .= '/api/';
        $url .= Config::get('gitlab-merge-requests.api_version');

        $this->endpoint = $url;
    }
}
