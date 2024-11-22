# Установка из Git
## Клонирование с GitHub
> git clone git@github.com:SergePlatanov/RealTracker.git RealTracker

## Переименовываем .env.example в .env
в файле .env меняем:<br>
APP_NAME<br>
APP_DEBUG пока можем оставить true<br>
APP_URL указываем доменное имя https://xxx.xxx.com<br>
DB_xxx - меняем все значения, их берем у хостинг провайдера<br>

Ставим composer локально
инструкция https://getcomposer.org/download/

sudo apt-get update
sudo apt-get install php8.2-intl php-zip php-fpm php-mysql php-gd php-bcmath
sudo apt-get install php8.2-sqlite3
php composer.phar update

sudo service nginx restart

генерируем ключ
php artisan key:generate

## Ставим node.js
sudo apt-get install curl
curl -sL https://deb.nodesource.com/setup_16.x | sudo bash -
sudo apt-get install nodejs

## Ставим tailwnd
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
В файл tailwind.config.js добавляем<br>
>module.exports = {<br>
>  content: [<br>
>>    "./resources/**/*.blade.php",<br>
>>    "./resources/**/*.js",<br>
>>    "./resources/**/*.vue",<br>
>  ],
>  theme: {<br>
>    extend: {},<br>
>  },<br>
>  plugins: [],<br>
>}

## Генерируем ключ
php artisan key:generate

## Установка пакетов и зависимостей
php composer.phar install<br>
npm install

https://laravel.com/docs/11.x/upgrade#sanctum

## Восстанавливаем базу данных
mysql -u fuser -p  factorydb < db.sql

## Восстанавливаем public
1. Копируем папку image
2. Делаем символьную ссылку на папку \storage\app\public<br>
   cd public<br>
   ln -s ../storage/app/public storage
   
## Перенос в Docker

