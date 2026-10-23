# TH3 - Product Management Service

TH3 is a separate RESTful service for product CRUD.

## 1. Architecture

```text
Client / Postman
      |
      | Bearer JWT
      v
TH3 Product Service :8001
      |
      | POST /auth + Bearer JWT
      v
TH2 Authentication Service :8000

TH3 owns its own database:
database/database.sqlite

TH2 owns a different database:
TH2-JWT-Authentication-Service/database/database.sqlite
```

The services do not share a database. TH3 only asks TH2 to validate the token.

## 2. Technology

- Laravel 13
- PHP 8.2+
- SQLite
- RESTful API
- TH2 JWT authentication
- TH3 port: `8001`
- TH2 port: `8000`

## 3. Installation

Open CMD in the TH3 project folder:

```cmd
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

TH3 does not need the JWT package because token generation and token validation belong to TH2.

## 4. Run both services

### Terminal 1 - TH2

```cmd
cd D:\SOA\TH2-JWT-Authentication-Service
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
php artisan serve --host=127.0.0.1 --port=8000
```

### Terminal 2 - TH3

```cmd
cd D:\SOA\TH3-Product-Management-Service
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve --host=127.0.0.1 --port=8001
```

## 5. Product API

All product endpoints require:

```text
Authorization: Bearer <JWT>
```

### Get all

`GET http://127.0.0.1:8001/products`

### Get one

`GET http://127.0.0.1:8001/products/{id}`

### Create

`POST http://127.0.0.1:8001/products`

```json
{
  "name": "Keyboard Logitech",
  "description": "Bàn phím không dây",
  "price": 850000,
  "quantity": 15
}
```

### Update

`PUT http://127.0.0.1:8001/products/{id}`

```json
{
  "name": "Keyboard Logitech K380",
  "description": "Bàn phím Bluetooth",
  "price": 990000,
  "quantity": 20
}
```

### Delete

`DELETE http://127.0.0.1:8001/products/{id}`

## 6. Authentication flow

1. Client calls `POST http://127.0.0.1:8000/login`.
2. TH2 checks `userName` and `password`.
3. TH2 returns a JWT.
4. Client sends the JWT to TH3.
5. TH3 middleware calls `POST http://127.0.0.1:8000/auth` with the same Bearer token.
6. If TH2 returns `valid: true`, TH3 continues to `ProductController`.
7. Otherwise TH3 returns `401`.
8. If TH2 cannot be reached, TH3 returns `503`.

## 7. Database

TH3 table:

| Column | Type |
|---|---|
| id | INT primary key |
| name | VARCHAR(255) |
| description | TEXT |
| price | DECIMAL(10,2) |
| quantity | INT |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

## 8. Suggested Postman order

1. TH2 - Login
2. Copy JWT to Postman variable `token`
3. TH2 - Validate Token
4. TH2 - Hello World
5. TH3 - Get Products
6. TH3 - Create Product
7. TH3 - Get Product
8. TH3 - Update Product
9. TH3 - Delete Product
10. TH3 request without token -> `401`

## 9. Why this matches the SOA requirement

- Authentication is an independent service.
- Product management is an independent service.
- They run on different ports.
- They use separate databases.
- Communication between services is through HTTP/REST/JSON.
- TH3 does not directly read TH2's database.
