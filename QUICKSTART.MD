# Environment
This project might not work with the latest verisons of PHP, npm, etc.

The primary components used in the application are:
- Laravel 7
- React 16.2
	- React Router 5.2
	- Material UI v4

The working environemnt includes:
- PHP 7.4.8
- MariaDB 10.4.13
- Node.js v14.16
	- npm 6.14.11

# First time setup
Copy the contents of `.env.example` to `.env` and run `php artisan key:generate`.

Change `APP_` values to your preference. `APP_USERNAME` and `APP_PASSWORD` are used for logging in.

Change `DB_` values to match your database.

Run the following commands to download the necessary dependencies, and prepare the database tables:
```
npm install
composer install
php artisan migrate:fresh
```

Run webpack with command `npm run dev` or `npm run watch`, and run the web server with `php artisan serve`.

You can also use the command `php artisan db:seed` to quickly fill the database with random players, teams, and events.

# Deployment
Configure the web server to serve files from the `public` directory.

Run `composer install --no-dev` to install/update composer dependencies, run `npm ci` to install/update npm dependencies.

Run webpack `npm run prod`.

Sometimes it might be necessary to clear the framework cache `php artisan cache:clear`.
