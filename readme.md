git clone git@github.com:AfanaskoAlexandr/gftest.git

cd testgf

composer install

sudo chmod -R 777 storage

sudo chmod -R 777 bootstrap/cache/

git config core.filemode false

cp .env.example .env

php artisan key:generate

В файле .env указать свои данные для подключения к postgresql

DB_CONNECTION=pgsql

DB_HOST=127.0.0.1

DB_PORT=5432

DB_DATABASE=gftest

DB_USERNAME=gftest

DB_PASSWORD=secret

php artisan migrate:refresh --seed

npm install

npm run dev

php artisan serve