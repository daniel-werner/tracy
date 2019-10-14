<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Tracy');

// Project repository
set('repository', 'https://github.com/daniel-werner/tracy.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('wernerd.info')
    ->set('user', 'deploy')
    ->set('deploy_path', '/var/www/vhosts/tracy.wernerd.info');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php7.2-fpm reload');
});

after('deploy', 'reload:php-fpm');
