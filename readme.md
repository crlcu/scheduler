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
php artisan migrate
php artisan db:seed
php artisan cache:clear
php artisan view:clear
php artisan route:cache
```

## Login

- User: ``administrator@scheduler.com``
- Password: ``password``
