# Gitlab Merge Requests

This package shows the merge requests from your GitLab user profile.

## Installation
You can install this using composer in your Laravel project:
```php
composer require koenhendriks/gitlab-merge-requests
```

## Config

Add the following data to your `.env` file:

```dotenv
GITLAB_API_KEY=xx
GITLAB_URL=https://git.company.com/
```

## Usage
Run the following artisan command after setting up the configuration:

```
php artisan gitlab:merge-requests
```

By default this will get the merge requests that have been merged in the last 7 days. You can pass in a integer in the first argument to get merge requests from a longer period of time.   

Get MR's from last 2 weeks:
```
php artisan gitlab:merge-requests 14
```



