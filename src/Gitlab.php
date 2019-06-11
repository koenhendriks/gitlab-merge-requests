<?php

namespace KoenHendriks\GitlabMergeRequests;

use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;

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
     * @var Client Guzzle client
     */
    private $client;
    /**
     * @var Command
     */
    private $command;

    /**
     * Gitlab constructor.
     * @param  Command  $command
     * @param  Client  $client  Guzzle client injected by Laravel's IoC.
     */
    public function __construct(Client $client)
    {
        $this->key = Config::get('gitlab-merge-requests.gitlab_api_key');
        $this->setEndpoint();
        $this->client = $client;
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

    /**
     * Api call to the
     * @return \Psr\Http\Message\StreamInterface|string
     * @throws \Exception
     */
    public function getMergeRequests(int $days)
    {
        $date = Carbon::now()->subDays($days)->toIso8601String();
        try {
            $mergeRequests = $this->client->get(
                $this->endpoint . '/merge_requests?' .
                'created_after=' . $date . '&' .
                'state=merged',
                ['headers' => $this->getHeaders()]
            );

        } catch (BadResponseException $exception) {
            if ($exception->getCode() == 401) {
                throw new \Exception('Unauthorized, check your GITLAB_API_KEY');
            } else {
                throw $exception;
            }
        }

        return \GuzzleHttp\json_decode($mergeRequests->getBody());
    }

    private function getHeaders(): array
    {
        return [
            'Private-Token' => $this->key,
            'Accept' => 'application/json',
            'User-Agent' => 'koenhendriks/gitlab-merge-requests'
        ];
    }
}
