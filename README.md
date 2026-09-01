# Установка из Git
## Клонирование с GitHub
> git clone git@github.com:SergePlatanov/RealTracker.git RealTracker

## Переименовываем .env.example в .env
в файле .env меняем:<br>
APP_URL указываем доменное имя https://xxx.xxx.com<br>
DB_xxx - меняем все значения, их берем у хостинг провайдера<br>

## Генерируем ключ
php artisan key:generate

## Установка пакетов и зависимостей
composer install<br>
npm install

## Восстанавливаем базу данных
mysql -u fuser -p  factorydb < db.sql

## Восстанавливаем public
1. Копируем папку image
2. Делаем символьную ссылку на папку \storage\app\public<br>
   ln -s ../storage/app/public public/storage
  

