# Quickstart
## Environment
As this project was created similarly to the original, some components might not work with the latest verisons of PHP, npm, etc.

The primary components used in the application are:
- Laravel 7
- React 16.2

The working environemnt includes:
- PHP 7.4.8
- MariaDB 10.4.13
- Node.js v14.16
	- npm 6.14.11

## Setup
Create an .env file similar to .env.example.
Change the database connection credentials to match your environment.
Run the following commands to download the necessary dependencies and generate an application key:
```
npm install
composer install
php artisan key:generate
```

Run webpack with the command `npm run watch` and run the web server with `php artisan serve`.
