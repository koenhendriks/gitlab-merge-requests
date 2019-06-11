<?php

return [
    'gitlab_api_key' => env('GITLAB_API_KEY'),
    'gitlab_url' => env('GITLAB_URL', 'https://gitlab.com/'),
    'api_version' => env('GITLAB_API_VERSION', 'v4')
];
