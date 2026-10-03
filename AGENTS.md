# AGENTS.md

## Стек

Laravel 13 (PHP ^8.4), Filament 5 — админка на `/admin` (`app/Providers/Filament/AdminPanelProvider.php`), Inertia + Vue 3, Tailwind 4, Vite, Livewire, Sanctum, Spatie permission/backup. БД — MySQL 8.4. CI в репо нет.

## Docker (весь рабочий процесс)

Локального PHP/Node не предполагается — всё через `docker compose`. Проект целиком примонтирован в контейнер `app` как `/var/www`.

- `docker compose --profile dev up -d` — nginx (:80), php-fpm, mysql, Vite dev-сервер с HMR (:5173)
- Любые artisan/composer команды: `docker compose exec app php artisan ...`
- Первый запуск: `composer install` → `php artisan key:generate` → `php artisan migrate` → `php artisan storage:link`
- `.env` копируется из `.env.example` (уже настроен под Docker: `DB_HOST=db`). Обязательно выставить `HOST_UID`/`HOST_GID` равными `id -u`/`id -g`, затем пересобрать: `docker compose build`
- Прод-сборка ассетов: `docker compose --profile build run --rm assets`
- Xdebug выключен по умолчанию; включается `XDEBUG_MODE=debug` в `.env` (IDE слушает 9003).

### Планировщик и бэкапы

- Контейнер `scheduler` (`php artisan schedule:work`) выполняет задачи из `routes/console.php` — он в базовом compose и стартует вместе с `app` без всяких профилей. Очередей в проекте нет (`QUEUE_CONNECTION=sync`); когда появятся — по той же схеме добавить контейнер `queue` с `php artisan queue:work`.
- Бэкапы — spatie/laravel-backup, ежедневно: `backup:clean` 02:00, `backup:run` 03:00 (дамп БД gzip + `storage/app/public`), `backup:monitor` 08:00 (алерты на `BACKUP_MAIL_TO`). Хранятся на дисках из `BACKUP_DISKS` (через запятую): локально `local` (`storage/app/private`), в проде `local,sftp` — параметры SFTP в `BACKUP_SFTP_*`. Ручной запуск: `docker compose exec app php artisan backup:run` или из Filament `/admin` (там же восстановление через wnx/laravel-backup-restore).

## Грабли

- **`CACHE_STORE`, а не `CACHE_DRIVER`** — в Laravel 13 старое имя переменной игнорируется (в `phpunit.xml` остался мусорный `CACHE_DRIVER=array`, он не работает).
- **Миграции падают, если cache store = `database`**: миграция spatie/permission сбрасывает кэш до создания таблицы `cache`, а миграции на cache/sessions-таблицы в репо нет. Держать `CACHE_STORE=file`.
- **`public/css`, `public/js`, `public/fonts`** — публикуемые ассеты Filament, пересоздаются при каждом `composer install` (хук `filament:upgrade`). В gitignore, не редактировать и не коммитить.
- **npm — только `npm ci`**: `npm install` переписывает lockfile; `npm run dev` — это разовая сборка, dev-сервер — `npm run dev:serve`.
- Контейнер `app` работает от `${HOST_UID}:${HOST_GID}` (владелец bind-маунта). Если контейнер создал root-файлы, host-`chown` не поможет — делать через `docker run --rm -v "$PWD":/w alpine chown -R <uid>:<gid> /w`.
- MySQL наружу торчит только на `127.0.0.1:${DB_FORWARD_PORT:-3306}`; при занятом порту переопределить в `.env`.
- Восстановление дампа — прямым импортом в контейнер: `docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "${DB_DATABASE}"' < db.sql` (дамп можно подать и с хоста через `127.0.0.1:${DB_FORWARD_PORT}`); автозагрузки дампов при старте контейнера нет.
- `DatabaseSeeder` пустой; `CreateAdminUserSeeder` и другие сидеры не зарегистрированы — запускать явно: `php artisan db:seed --class=CreateAdminUserSeeder`.

## Тесты

Pest + PHPUnit, сьюты `tests/Unit` и `tests/Feature`. Перед первым запуском нужны БД и права (пользователь из `.env` не имеет доступа к базе `testing`):

```bash
docker compose exec db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS testing; GRANT ALL ON testing.* TO \"${MYSQL_USER}\"@\"%\"; FLUSH PRIVILEGES;"'
docker compose exec app php artisan test
```

Часть Feature-тестов (`tests/Feature/Auth/*`) — устаревший Breeze-бойлерплейт и падает на чистом окружении (например, роут `verification.verify` в приложении не определён). Перед выводами о регрессии сверяться: падение было до ваших изменений или нет.

## Прочее

- `README.md` описывает ручную установку без Docker и восстановление из несуществующего в репо `db.sql` — частично устарел, источник правды по запуску — этот файл и `docker-compose.yml`.
