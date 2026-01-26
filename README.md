# Laravel JWT Authentication Backend

A production-ready Laravel backend with JWT authentication and PostgreSQL integration.

## Features

- ✅ User registration with automatic 'USER' role assignment
- ✅ User login with JWT token generation
- ✅ JWT-based authentication for protected routes
- ✅ Token invalidation on logout (blacklist)
- ✅ Get authenticated user information
- ✅ List all registered users
- ✅ Request validation with custom error messages
- ✅ API Resources for consistent response formatting
- ✅ Centralized exception handling
- ✅ Password hashing (bcrypt)
- ✅ PostgreSQL database

## Tech Stack

- **Laravel**: 12.48.1
- **JWT Auth**: tymon/jwt-auth 2.2.1
- **Database**: PostgreSQL
- **PHP**: 8.2+

## Quick Start

### 1. Configure Database

Edit `.env` and update PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=role_management
DB_USERNAME=your_postgres_username
DB_PASSWORD=your_postgres_password
```

### 2. Create Database

```bash
psql -U postgres
CREATE DATABASE role_management;
\q
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Start Server

```bash
php artisan serve
```

API available at: `http://localhost:8000`

## API Endpoints

### Public Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register new user |
| POST | `/api/login` | Login user |

### Protected Routes (Require JWT Token)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/me` | Get authenticated user |
| POST | `/api/logout` | Logout (invalidate token) |
| GET | `/api/users` | Get all users |

## Usage Examples

### Register

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"password123"}'
```

### Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"password123"}'
```

### Get Current User

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Get All Users

```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Database Schema

### Users Table

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | Primary Key |
| username | string | Unique, Required |
| password | string | Hashed, Required |
| role | string | Default: 'USER' |
| created_at | timestamp | Auto |
| updated_at | timestamp | Auto |

## Security Features

- **Password Hashing**: Automatic bcrypt hashing with 12 rounds
- **JWT Blacklist**: Tokens invalidated on logout
- **Request Validation**: Input validation with custom error messages
- **Hidden Passwords**: Passwords never exposed in API responses
- **Exception Handling**: Centralized JWT exception handling
- **Environment Config**: Sensitive data in `.env` file

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php    # Authentication endpoints
│   │   └── UserController.php    # User management endpoints
│   ├── Requests/
│   │   ├── RegisterRequest.php   # Registration validation
│   │   └── LoginRequest.php      # Login validation
│   └── Resources/
│       └── UserResource.php      # User data transformation
└── Models/
    └── User.php                  # User model with JWT integration

config/
├── auth.php                      # JWT guard configuration
└── jwt.php                       # JWT settings

database/migrations/
└── 0001_01_01_000000_create_users_table.php

routes/
└── api.php                       # API routes
```

## JWT Token Payload

```json
{
  "id": 1,
  "username": "testuser",
  "role": "USER",
  "iat": 1706265000,
  "exp": 1706268600
}
```

## Response Format

### Success Response (Register/Login)

```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "username": "testuser",
    "role": "USER",
    "created_at": "2026-01-26T09:30:00.000000Z",
    "updated_at": "2026-01-26T09:30:00.000000Z"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Error Response

```json
{
  "message": "Invalid credentials"
}
```

## License

Open-source software licensed under the MIT license.
