@servers(['manage-expenses.com' => 'root@manage-expenses.com', 'testing4' => 'itdept@daisycentraltesting4.daisygroupplc.com',])

@setup
	$path = isset($path) ? $path : '/var/www/html/scheduler';
@endsetup

@task('deploy', ['on' => $on])
    cd {{ $path }}
    git pull origin master
    composer install
    php artisan migrate
    php artisan cache:clear
    php artisan view:clear
    php artisan route:cache
@endtask
