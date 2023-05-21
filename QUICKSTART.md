# Environment
The working environemnt includes:
- PHP 8.2.4
- Node.js v18.16
	- npm 9.6.4

# First time setup
* Copy `.env.example` to `.env`.
* Run `php artisan key:generate`.
* Change `APP_*` values to your preference.
	* `APP_USERNAME` and `APP_PASSWORD` are used for logging in.
* Create a database for this project if one does not exist already.
* Change `DB_*` values to match your database.

# Run locally
* Run `npm install`.
* Run `composer install`.
* Run `php artisan migrate:fresh` to create tables in the database.
* Change `.env` values.
	* Change `APP_ENV` to `local`.
	* Change `APP_DEBUG` to `true`.
* Run a local web server with `php artisan serve`.
* Run laravel mix `npm run development`.
	* Alternatively, run `npm run watch` to refresh the browser on every update continuously with browsersync.

# Develop efficiently
* Run `php artisan db:seed` to quickly fill the database with random players, teams, and events.
* Run static analysis with `./vendor/bin/phpstan analyse app database --level max`.

# Deploy
* Configure the web server (apache, nginx) to serve files from the `public/` directory.
* Run `composer install --no-dev` to install/update composer dependencies.
* Run `npm ci` to install/update npm dependencies.
* Run laravel mix `npm run production`.
* Sometimes it might be necessary to clear the framework cache `php artisan cache:clear`.
