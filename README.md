![Laravel](https://github.com/Mick00/Minevote/workflows/Laravel/badge.svg)

# Requirements
- Docker
- Docker Compose

# Installation

## Dev install

1. Clone the repo
2. `docker compose up -d`
3. SSH in the app container and run `composer install` to install PHP dependencies
3. Run `npm install` to install JS dependencies
4. Copy `.env.example` to `.env` and set your db username and password
4. Run `php artisan key:generate` to create the encryption key
5. Run `php artisan migrate` to create all tables in the database
6. Run `php artisan db:seed --class=[DevSeeder|BasicSeeder]` to seed the database. DevSeeder also adds servers and users, while the BasicSeeder adds essential stuff.
7. Run `php artisan storage:link` to configure storage symlinks.
8. Compile styles `npm run prod`

# Develop

1. SSH into the app container
2. Run `php artisan serve --host 0.0.0.0`

You can watch and auto reload with `npm run watch`
