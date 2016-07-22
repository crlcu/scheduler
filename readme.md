# Scheduler

- Scheduler is a tool for easily managing cron like tasks.
- Inside Scheduler you can have repetitive tasks as well as one time only tasks which can run locally or via SSH.
- Scheduler also alows you to define notifications regarding task execution.
- Notifications can be sent via email or as a message on Slack.

## Instalation
```
composer install
php -r "copy('.env.example', '.env');"
php artisan key:generate
```
Now setup ``.env``. Then
```
php artisan install
```

## Crontab
``* * * * * php <path to project>/artisan schedule:run | sed -e "s/^/$(date +'\%Y-\%m-\%d \%T') /" >> <path to project>/storage/logs/crontab-$(date +'\%Y-\%m-\%d').log 2>&1``

## Login

- User: ``administrator@scheduler.com``
- Password: ``password``

- User: ``user@scheduler.com``
- Password: ``password``
