how to run:
1. [run]: composer install
2. [run]: cp .env.example .env
3. [change]: env file
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=travelproject
    DB_USERNAME=root
    DB_PASSWORD=my_password
4. [run]: php artisan key:generate
5. [run]: php artisan serve
