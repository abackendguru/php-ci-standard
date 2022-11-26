<?php
/**
 * Simple CI with deployer php for PHP Project
 */

namespace Deployer;

/**
 * Pre requites
 */
// https://deployer.org/docs/7.x/recipe/common
require 'recipe/common.php';    

// Define project constants
const REMOTE_USER = 'developer';
const REMOTE_IP = '104.248.158.47';
const DEPLOY_PATH = '~/develop/php-ci-standard';


/**
 * 1. Configurations
 */

set('repository', 'git@github.com:abackendguru/php-ci-standard.git');

// My LAravel project is in a sub folder of the git branch
set('rsync_src', function () {
    return __DIR__ ;
});

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);


/**
 * 2. Define hosts
 */
host('develop')
    ->setLabels([
        'type' => 'web',
        'env' => 'development',
    ])
    ->setHostname(REMOTE_IP)
    ->set('remote_user', REMOTE_USER)
    ->set('deploy_path', DEPLOY_PATH)
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod');


/**
 * 3. Define tasks
 */

// Custom deploy:upload task to use the www directory in this project 
task('deploy:upload', function () {
    upload('./', '{{release_path}}/');
});

// Prepares a new release in temporary dir
task('deploy:prepare', [
    'deploy:info',
    'deploy:setup',     // Prepares host for deploy
    'deploy:lock',      // Locks current deployment status
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
])->desc('Prepares a new release');

// Publishes the release to current if all task success
task('deploy:publish', [
    'deploy:symlink',
    'deploy:unlock',
    'deploy:cleanup',
    'deploy:success',
])->desc('Publishes the release');


/**
 * 4. Main task deploy
 * 
 *  1. Check info
 *  2. Upload file
 *  3. Install composer
 *  4. Run tests
 *  5. Publish if all step success
 * 
 */ 

task('deploy', [
    // deployer required tasks in ordered
    'deploy:prepare',
    'deploy:upload',
    'deploy:vendors',
    'deploy:publish',
])->desc('Main task');


/**
 * 4. Define hooks
 */

after('deploy:failed', 'deploy:unlock');

/**
 * Usage examples:
 * 
 *  - Deploy to develop: deployer deploy develop
 *  - Deploy to production: deployer deploy prod
 */