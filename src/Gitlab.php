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
     * @var array with arguments set in the command.
     */
    private $options;

    /**
     * @var Client Guzzle client
     */
    private $client;

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
    private function setEndpoint(): void
    {
        // Right trim the last '/' so even if the user sets this in the config we still get the correct url
        $url = rtrim(Config::get('gitlab-merge-requests.gitlab_url'), '/');
        $url .= '/api/';
        $url .= Config::get('gitlab-merge-requests.api_version');

        $this->endpoint = $url;
    }

    /**
     * Api call to the
     * @param  int  $days
     * @return array
     * @throws \Exception
     */
    public function getMergeRequests(int $days): array
    {
        $date = Carbon::now()->subDays($days)->toIso8601String();

        try {
            $mergeRequests = $this->client->get(
                $this->createUrl($date),
                ['headers' => $this->getHeaders()]
            );

        } catch (BadResponseException $exception) {
            if ($exception->getCode() == 401) {
                throw new \Exception('Unauthorized, check your GITLAB_API_KEY');
            } else {
                throw $exception;
            }
        }
        dump($this->createUrl($date));
        return \GuzzleHttp\json_decode($mergeRequests->getBody());
    }

    /**
     * Get the arguments formatted as URL.
     *
     * @return string
     */
    private function getOptionsUrl(): string
    {
        $url = '';
        foreach ($this->options as $option => $value) {
            $url .= $option . '=';
            if (is_array($value)) {
                foreach ($value as $item) {
                    $url .= $item . ',';
                }
                $url = rtrim($url, ',');
                $url .= '&';
            } else {
                $url .= $value . '&';
            }
        }
        return rtrim($url, '&');
    }

    private function getHeaders(): array
    {
        return [
            'Private-Token' => $this->key,
            'Accept' => 'application/json',
            'User-Agent' => 'koenhendriks/gitlab-merge-requests'
        ];
    }

    /**
     * Set arguments for the call
     * @param  array  $arguments
     */
    public function setOptions(array $arguments): void
    {
        // Default Laravel console options we don't need.
        unset($arguments["help"]);
        unset($arguments["quiet"]);
        unset($arguments["verbose"]);
        unset($arguments["version"]);
        unset($arguments["ansi"]);
        unset($arguments["no-ansi"]);
        unset($arguments["no-interaction"]);
        unset($arguments["env"]);

        $this->options = $arguments;
    }

    /**
     * Create the url to do the request.
     *
     * @param  string  $date
     * @return string Url to request to.
     */
    private function createUrl(string $date): string
    {
        return $this->endpoint . '/merge_requests?' .
            'created_after=' . $date . '&' .
            $this->getOptionsUrl();
    }
}
