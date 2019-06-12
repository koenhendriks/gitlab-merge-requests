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

```bash
php artisan gitlab:merge-requests
```

By default this will get the merge requests that have been merged in the last 7 days. You can pass in a integer in the first argument to get merge requests from a longer period of time.   

Get MR's from last 2 weeks:
```bash
php artisan gitlab:merge-requests 14
```

Get MR's from last 2 weeks with specific labels:
```bash
php artisan gitlab:merge-requests 14 --labels=feature --labels=bug
```

For all options use the `-h` option provided by Laravel.
```bash
php artisan gitlab-merge-requests -h
```

```bash
Options:
      --wip[=WIP]              Filter merge requests against their wip status. 'yes' to return only WIP merge requests, no to return non WIP merge requests [default: "no"]
      --state[=STATE]          Return all merge requests or just those that are opened, 'closed', 'locked', or 'merged [default: "merged"]
      --order_by[=ORDER_BY]    Return requests ordered by 'created_at' or 'updated_at' fields. [default: "created_at"]
      --sort[=SORT]            Return requests sorted in 'asc' or 'desc' order. [default: "desc"]
      --labels[=LABELS]        Return merge requests matching labels. 'None' lists all merge requests with no labels. 'Any' lists all merge requests with at least one label. Predefined names are case-insensitive. (multiple values allowed)
      --scope[=SCOPE]          Return merge requests for the given scope: 'created_by_me', 'assigned_to_me' or 'all'. [default: "all"]
      --milestone[=MILESTONE]  Return merge requests for a specific milestone. 'None' returns merge requests with no milestone. 'Any' returns merge requests that have an assigned milestone. [default: "any"]

```

