**how to run (run in the root directory)**
1. [run]: composer install
2. [run]: cp .env.example .env
3. [change]: env file<br>
    DB_CONNECTION=mysql<br>
    DB_HOST=127.0.0.1<br>
    DB_PORT=3306<br>
    DB_DATABASE=travelproject<br>
    DB_USERNAME=root<br>
    DB_PASSWORD=my_password<br>
4. [run]: php artisan key:generate
5. [run]: php artisan serve
