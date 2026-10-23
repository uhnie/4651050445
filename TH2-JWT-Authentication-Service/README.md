# TH2 - JWT Authentication Service

TH2 is the authentication service used by TH3 Product Management Service.

## 1. Technology

- Laravel 13
- PHP 8.2+
- JWT: `php-open-source-saver/jwt-auth`
- SQLite
- RESTful API
- Port: `8000`

## 2. Demo account

```text
userName: admin
password: 123456
```

The password is stored as a hash in the database.

## 3. Installation

Open CMD in this project folder:

```cmd
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
```

If `database/database.sqlite` does not exist, create an empty file with that name before running the migration.

## 4. Run

```cmd
php artisan serve --host=127.0.0.1 --port=8000
```

Base URL:

```text
http://127.0.0.1:8000
```

## 5. API

### Login

`POST /login`

```json
{
  "userName": "admin",
  "password": "123456"
}
```

Returns a JWT token.

### Validate token

`POST /auth`

Header:

```text
Authorization: Bearer <JWT>
```

Returns:

```json
{
  "valid": true,
  "authenticated": true
}
```

### Hello World

`GET /hello`

Header:

```text
Authorization: Bearer <JWT>
```

This endpoint is protected by the `jwt` middleware.

## 6. Relation to TH3

TH3 does not share TH2's database. TH3 calls:

```text
POST http://127.0.0.1:8000/auth
```

to validate the Bearer token.

That keeps authentication and product management as two separate services.
