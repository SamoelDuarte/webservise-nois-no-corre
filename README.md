# Web service Nois no Corre

Painel administrativo em Laravel 12 com SQLite. Requer PHP 8.2 ou superior, Composer e a extensão PDO SQLite.

## Instalação em outra máquina

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Abra http://127.0.0.1:8000/login. A conta inicial de desenvolvimento é `admin@admin.com` com senha `123123`. Troque a senha antes de disponibilizar o painel publicamente.

No Apache, a raiz pública da aplicação deve apontar para `public/`.

## Testes

```powershell
php artisan test
```