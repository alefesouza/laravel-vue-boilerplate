# Laravel Vue Boilerplate

<p align="center">
  <img src="logo.png" />
</p>

A Laravel 5.5 SPA application boilerplate using Vue.js 2.5, Bootstrap 4, TypeScript, Sass and Pug with:

* A users CRUD if the current user is an admin.
* i18n for English and Portuguese, based on browser language settings.
* Vue component tests using Jest and API tests using PHPUnit.
* Already configured to run tests on Docker and GitLab CI.
* Dockerfile configured with PHP 7, Node.js 8, Yarn and Composer, with MySQL and phpMyAdmin on Docker Compose.

You can check it live [on this website](https://alefesouza.com/github/laravel-vue-boilerplate), with the credentials:

Admin user

    E-mail: admin@example.com
    Password: admin

Normal user

    E-mail: normal@example.com
    Password: normal

Notice that all the changed data on that website sample, such as password and CRUD actions, won't be stored.

## Main dependencies

Front-end:

* [Vue](https://github.com/vuejs/vue)
* [VueRouter](https://github.com/vuejs/vue-router)
* [Vuex](https://github.com/vuejs/vuex)
* [vuex-i18n](https://github.com/dkfbasel/vuex-i18n)
* [Bootstrap 4](https://github.com/twbs/bootstrap)
* [BootstrapVue](https://github.com/bootstrap-vue/bootstrap-vue/)
* [Font Awesome](https://github.com/FortAwesome/Font-Awesome)
* [Pug](https://github.com/pugjs/pug)
* [Sass](https://github.com/sass/node-sass)
* [Laravel Mix](https://github.com/JeffreyWay/laravel-mix)
* [Jest](https://github.com/facebook/jest)

Back-end:

* [Laravel](https://github.com/laravel/laravel)
* [Laravel HTMLMin](https://github.com/HTMLMin/Laravel-HTMLMin)
* [laravel-vue-i18n-generator](https://github.com/martinlindhe/laravel-vue-i18n-generator)
* [PHPUnit](https://github.com/sebastianbergmann/phpunit)

## Steps to run it:

Remember to search for "TODO change" on the files to change example code.

### With Docker

Run:

    docker-compose up --build

After it starts, just on the first time, run on another terminal:

    docker exec -it laravel-vue-boilerplate composer run docker && yarn docker

The application will be available on http://localhost:8000 and the phpMyAdmin on http://localhost:8080

### Common way

Rename the .env.example to .env, and fill it and the .env.testing with your local info, then:

Install PHP and JavaScript dependencies:

    composer install
    npm install

Generate Laravel keys:

    php artisan key:generate
    php artisan key:generate --env=testing

Generate i18n string for Vue, based on Laravel i18n files:

    php artisan vue-i18n:generate

Migrate the database:

    php artisan migrate

Seed the database:

    php artisan db:seed

Compile all the front-end stuff:

    npm run prod

Test:

    composer test
    npm test
