# Environment
This project might not work with the latest verisons of PHP, npm, etc.

The primary components used in the application are:
- Laravel 7
- React 17.0
	- MUI v5

The working environemnt includes:
- PHP 7.4
- Node.js v16.17
	- npm 8.15

# First time setup
Copy the contents of `.env.example` to `.env` and run `php artisan key:generate`.

Change `APP_` values to your preference. `APP_USERNAME` and `APP_PASSWORD` are used for logging in.

Change `DB_` values to match your database. Create a database for this project if one does not exist already.

Run the following commands to download the necessary dependencies, and prepare the database tables:
```
npm install
composer install
php artisan migrate:fresh
```

# Run locally
Run laravel mix with command `npm run development`, and run the web server with `php artisan serve`.
You can use `npm run watch` to run continuously with browsersync refreshing the browser on every update.

The command `php artisan db:seed` can be used to quickly fill the database with random players, teams, and events.

# Deployment
Configure the web server to serve files from the `public` directory.

Run `composer install --no-dev` to install/update composer dependencies, run `npm ci` to install/update npm dependencies.

Run laravel mix `npm run prod`.

Sometimes it might be necessary to clear the framework cache `php artisan cache:clear`.
