# Gitlab hooks change statuses in redmine

## Dependencies:

    git
    docker
    docker-compose
    make

## Redmine config:
Run command: `make init` to deploy the project(Redmine+Gitlab+Endpoint)
Or execute command from make file at the root of the project.

## Redmine config:
Redmine host:
http://localhost:8080/  login/pass: admin/admin
Add trackers statuses and sequence of actions to make tests project.
In this example, the following statuses are used:
'In work' => int 1
'Merge request' => int 2
'For revision' => int 3
'Test' => int 4

## Gitlab config:
Gitlab host:
http://localhost:8929/ login: root
1.  Add test project.
    Allow requests to the local network from hooks and services:
    Go: http://localhost:8929/admin/application_settings/network -> Outbound requests -> add `nginx:80`

2.  Add webhooks (Administrator->your_test_project_name->Webhook Settings);
    URL for endpoint: http://nginx:80/set-redmine-status/
    Check Merge request events and Comments

## Endpoint config:
Install dependencies with composer:
cd src
composer install

create file .env at the project root (/src)
Go redmine->settings->api->Enable rest web service->my account->get api key
.env example: REDMINE_API_KEY="{your api key from redmine settings}"
Do basic setup for redmine settings and add test task.

## Test the status changes:
1. Create a new branch in the test project.
2. Push origin new branch (login: root pass: root or password created during registration)
3. Create merge request - check the result in redmine(`Merge request` and link to merge request).
4. Add new note - check the result in redmine(`For revision`).
4. Submit merge request - check the result in redmine(`Test`).