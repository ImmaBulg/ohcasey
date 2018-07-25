# Чистая установка:
Клонировать репозиторий

Установить php зависимость

imagemagick

(sudo pecl install imagick)

Установить redis

composer update

php artisan vendor:publish

php artisan ide-helper:generate

npm install

npm run dev (production для боевого)

создать символьные ссылки
ln -s storage/app/device public/storage/device
ln -s storage/app/smile public/storage/smile
ln -s storage/app/generated public/storage/sz
ln -s storage/app/upload public/storage/upload

Развернуть дамп базы из database/dump2017-03-25.sql.zip
Выполнить php artisan migrate

#CD
pg_dump --dbname=postgresql://ohcasey:ohcasey@127.0.0.1:5432/ohcasey > dump$(date +%Y-%m-%d).sql

cp -R www dump$(date +%Y-%m-%d)

cd www

git checkout .

git checkout master

git pull origin master --force

composer update

php artisan cache:clear

php artisan view:clear

php artisan route:clear

php artisan config:clear

redis-cli flushall

php artisan migrate --force

npm update

npm run production

cd ../

chown -R www-data:www-data ./www