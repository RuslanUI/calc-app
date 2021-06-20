## Запуск Laravel
Запуск через консоль Ubuntu 20.04:

- Создать папку проекта, перейти в нее и установить Composer https://getcomposer.org/download/

- Выполнить команду:
```bash
git clone https://github.com/RuslanUI/calc-app .
```
- Установить Sail:
```bash
composer require laravel/sail --dev
php artisan sail:install
```

- Установить все зависимости через Composer
```bash
composer install
```

- Запустить проекта из папки 
```bash
./vendor/bin/sail up
```

- Сайт будет доступен по ссылке http://localhost
- Выполнить миграции командой
```bash
php artisan migrate:refresh --seed
```

## Процесс создания переменных окружения и установке зависимостей

При запуске команды `./vendor/bin/sail up` генерируются переменные окружения (.env) и загружаются/подключаются зависимости из composer.json
