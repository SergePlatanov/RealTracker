# Установка из Git
## Клонирование с GitHub
> git clone git@github.com:SergePlatanov/RealTracker.git RealTracker

## Переименовываем .env.example в .env
в файле .env меняем:<br>
APP_NAME<br>
APP_DEBUG пока можем оставить true<br>
APP_URL указываем доменное имя https://xxx.xxx.com<br>
DB_xxx - меняем все значения, их берем у хостинг провайдера<br>

## Ставим node.js
sudo apt-get install curl
curl -sL https://deb.nodesource.com/setup_16.x | sudo bash -
sudo apt-get install nodejs

## Генерируем ключ
php artisan key:generate

## Установка пакетов и зависимостей
php composer.phar install<br>
npm unstall

## Восстанавливаем базу данных
mysql -u fuser -p  factorydb < db.sql

## Восстанавливаем public
1. Копируем папку image
2. Делаем символьную ссылку на папку \storage\app\public<br>
   cd public<br>
   ln -s storage/public storage
   

