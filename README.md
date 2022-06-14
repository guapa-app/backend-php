# ![Cosmo Laravel Backend](./public/landing/img/Logo-2.png)

Backend Made by cooperation between [Nozom](https://nozom.io) and [AQuadic](https://aquadic.com)

----------

# Getting started

## Important Links
- [Nozom](https://nozom.io)
- [AQuadic](https://aquadic.com)
- [Api Documentations](https://guapa.com.sa/docs/)
- [Live Backend](https://guapa.com.sa/)

## Script Requirement
This script assumes you have the requirement as the following:

- PHP Version: `^8.0`
- working mailing service with unlimited mails. so we can use it in verify mails and forget passwords.
- Access to [AQ Nova Repo.](https://github.com/AQuadic/nova_laravel)
- MySQL 5.7 or higher is required
- check spatie media library [requirements.](https://spatie.be/docs/laravel-medialibrary/v9/requirements)

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation#installation)

Clone the repository

    git clone https://github.com/AQuadic/cosmo_laravel.git

Switch to the repo folder

    cd cosmo_laravel

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate:fresh

Link storage media to public folder, this is needed for spatie laravel package.

    php artisan storage:link

Create system roles. (**this is must before creating any admins**)

    php artisan roles:setup

Create passport tokens. (**update it in .env file**)

    php artisan passport:install

Create passport admin token. (**update it in .env file**)

    php artisan passport:client --password --provider=admins

Build React Admin Panel

    npm install
    npm run prod

You can create as many as nova users as you want from this cmd.

    php artisan nova:user

Start the local development server for local development

    php artisan serve

You can now access the server at http://localhost:8000

Run the database seeder and you're done.

    php artisan db:seed

## Environment variables

- `.env` - Environment variables can be set in this file
