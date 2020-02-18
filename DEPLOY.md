## <a name="continuous-deploy"></a> Continuous Deploy

I've configured continuous deploy for this project thinking to use it on a shared host, since shared hosts usually don't have Node.js to compile front-end stuff, I've created the [build/deploy.sh](./build/deploy.sh) file for the CI environment compile it and push all the project with compiled assets to a deploy branch, which will pull on the webserver with Laravel Envoy, you can change the Envoy.blade.php file as you want to work for you if you can install Node.js on your webserver.

You can read [this tutorial](https://docs.gitlab.com/ee/ci/examples/laravel_with_gitlab_and_envoy) to learn more about it and how to add your SSH keys on GitLab.

I've created some secret variables to use on it:

* DEPLOY_GIT_EMAIL - user.email used by the CI environment.
* DEPLOY_GIT_USERNAME - user.name used by the CI environment.
* REMOTE_REPOSITORY - The SSH link to the remote repository you're working.
* PRODUCTION_URL - To use on GitLab CI Environments.

And of course, the SSH_PRIVATE_KEY variable.

Laravel Envoy:

* DEPLOY_USER - SSH address to your production server, in the format user@host.
* DEPLOY_PORT - SSH port.
* DEPLOY_PATH - Absolute path to the project folder.

Environment:

I use a shared host, it has an old PHP version by default and cannot have composer on PATH, so I've created more two secret variables to run PHP 7 and execute composer file, it is:

* PHP_COMMAND - To me it's like "/opt/php72/bin/php", because the default PHP version on my shared host account is PHP 5.2 (yeah).
* COMPOSER_COMMAND - To me it's like "/opt/php72/bin/php ~/composer.phar", because I can't add composer on PATH.

If you don't set these variable, it will execute the default "php" and "composer" command.
