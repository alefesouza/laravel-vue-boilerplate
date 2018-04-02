@setup
    $REMOTE_REPOSITORY = env('REMOTE_REPOSITORY');

    $DEPLOY_USER = env('DEPLOY_USER');
    $DEPLOY_PORT = env('DEPLOY_PORT');
    $DEPLOY_PATH = env('DEPLOY_PATH');

    $RELEASES_DIR = $DEPLOY_PATH . '/releases';
    $RELEASE = date('YmdHis');
    $NEW_RELEASE_DIR = $RELEASES_DIR .'/'. $RELEASE;

    $php = env('PHP_COMMAND') ?: 'php';
    $composer = env('COMPOSER_COMMAND') ?: 'composer';

    if (empty($DEPLOY_USER) || empty($REMOTE_REPOSITORY)) {
        throw new Exception('DEPLOY_USER and REMOTE_REPOSITORY env variables must be specified.');
    }
@endsetup

@servers(['web' => $DEPLOY_USER . ' -p ' . $DEPLOY_PORT])

@story('deploy', ['on' => 'web'])
    clone_repository
    run_composer
    update_symlinks
    cache
    migrate
@endstory

@task('clone_repository')
    [ -d {{ $RELEASES_DIR }} ] || mkdir {{ $RELEASES_DIR }}
    git clone --depth 1 -b deploy {{ $REMOTE_REPOSITORY }} {{ $NEW_RELEASE_DIR }}
@endtask

@task('run_composer')
    cd {{ $DEPLOY_PATH }}

    if [ ! -f '.env' ]; then
        echo 'Please create a .env file on the server and try again.'
        exit 1
    fi

    cd {{ $NEW_RELEASE_DIR }}
    {{ $composer }} install --no-dev --prefer-dist --no-scripts -q -o
@endtask

@task('update_symlinks')
    echo 'Linking storage directory'

    rm -rf {{ $NEW_RELEASE_DIR }}/storage
    ln -nfs {{ $DEPLOY_PATH }}/storage {{ $NEW_RELEASE_DIR }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $DEPLOY_PATH }}/.env {{ $NEW_RELEASE_DIR }}/.env

    echo 'Linking current release'
    ln -nfs {{ $NEW_RELEASE_DIR }} {{ $DEPLOY_PATH }}/current
@endtask

@task('cache')
    echo 'Running cache stuff'

    cd {{ $NEW_RELEASE_DIR }}

    {{ $php }} artisan clear-compiled
    {{ $php }} artisan config:cache
    {{ $php }} artisan route:cache
@endtask

@task('migrate')
    echo 'Migrate database'

    cd {{ $NEW_RELEASE_DIR }}

    {{ $php }} artisan down
    {{ $php }} artisan migrate --force
    {{ $php }} artisan up
@endtask
