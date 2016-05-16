@servers(['manage-expenses.com' => 'root@manage-expenses.com', 'testing4' => 'itdept@daisycentraltesting4.daisygroupplc.com',])

@setup
	$path = isset($path) ? $path : '/var/www/html/scheduler';
@endsetup

@task('deploy', ['on' => $on])
    cd {{ $path }}
    git pull origin master
    composer update
    php artisan migrate
@endtask
