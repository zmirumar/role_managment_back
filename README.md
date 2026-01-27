# 🚀 Laravel RBAC & Blog Management System

A high-performance, professional Laravel 12 backend featuring **JWT-based Authentication**, a dynamic **Role-Based Access Control (RBAC)** system, and a secured **Blog Management Layer**.

---

## 🌟 Key Features

-   **🔐 Secure Authentication**: Stateless JWT authentication via `tymon/jwt-auth`.
-   **🛡️ Granular RBAC**: Highly flexible Role and Permission system.
-   **📝 Content Management**: Secure CRUD for blog posts with permission enforcement.
-   **⚙️ Dynamic Administration**: `OWNER` dashboard for real-time user role and permission management.
-   **✅ Input Validation**: Strictly typed request validation using Laravel FormRequests.
-   **🎨 Premium Code Quality**: PSR-12 compliant, clean architecture, and optimized queries.

---

## 🛠️ Tech Stack

-   **Framework**: [Laravel 12.x](https://laravel.com/)
-   **Auth**: [JWT-Auth](https://github.com/tymondesigns/jwt-auth)
-   **Database**: [PostgreSQL](https://www.postgresql.org/)
-   **Language**: [PHP 8.2+](https://www.php.net/)

---

## ⚙️ Installation & Setup

### 1. Prerequisites
Ensure you have the following installed:
- PHP 8.2+
- Composer
- PostgreSQL

### 2. Clone and Install
```bash
git clone <repository-url>
cd role_managment_backend
composer install
```

### 3. Environment Configuration
Copy the example environment file and update your database credentials:
```bash
cp .env.example .env
```
Update these lines in your `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=role_management
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Database Setup
Run migrations and populate the initial roles/permissions:
```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

### 5. Authentication Setup
Generate the JWT secret key:
```bash
php artisan jwt:secret
```

### 6. Start the Server
```bash
php artisan serve
```

---

## 🏗️ RBAC Architecture

The system uses a hierarchy of roles, each mapped to specific permissions.

### 👥 Roles & Hierarchy
| Role | Title | Description |
| :--- | :--- | :--- |
| `USER` | Customer | Standard user. Access to read posts only. |
| `ADMIN` | Editor | Can create new posts. |
| `SUPERADMIN` | Manager | Can create, edit, and delete posts. |
| `OWNER` | System Admin | Bypasses all guards. Can manage users, roles, and permissions. |

### 🔑 Permissions
| Slug | Action |
| :--- | :--- |
| `create_post` | Can create a new blog entry. |
| `edit_post` | Can modify existing blog entries. |
| `delete_post` | Can remove blog entries from the system. |

---

## 📚 API Reference & CURL Commands

### 1. Authentication Endpoints

#### **Register User**
Create a new account. All registered users are assigned the `USER` role by default.
```bash
curl -X POST http://localhost:8000/api/register \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "username": "johndoe",
           "password": "password123"
         }'
```

#### **Login User**
Authenticates and returns a JWT Bearer token.
```bash
curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "username": "johndoe",
           "password": "password123"
         }'
```

#### **Get Profile (Protected)**
Get details of the currently authenticated user.
```bash
curl -X GET http://localhost:8000/api/me \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json"
```

---

### 2. Blog Management Endpoints

#### **List All Posts (Public)**
```bash
curl -X GET http://localhost:8000/api/posts \
     -H "Accept: application/json"
```

#### **Create Post (Requires `create_post`)**
```bash
curl -X POST http://localhost:8000/api/posts \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -d '{
           "title": "My Awesome Post",
           "content": "This is the content of the post."
         }'
```

#### **Update Post (Requires `edit_post`)**
```bash
curl -X PUT http://localhost:8000/api/posts/{id} \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -d '{
           "title": "Updated Title",
           "content": "Updated Content"
         }'
```

#### **Delete Post (Requires `delete_post`)**
```bash
curl -X DELETE http://localhost:8000/api/posts/{id} \
     -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

### 3. Owner/Admin Dashboard (OWNER Only)

#### **List All Users**
```bash
curl -X GET http://localhost:8000/api/admin/users \
     -H "Authorization: Bearer OWNER_TOKEN_HERE"
```

#### **Change User Role**
```bash
curl -X PUT http://localhost:8000/api/admin/users/{user_id}/role \
     -H "Authorization: Bearer OWNER_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -d '{
           "role": "ADMIN"
         }'
```

#### **List Roles & Permissions**
```bash
curl -X GET http://localhost:8000/api/admin/roles \
     -H "Authorization: Bearer OWNER_TOKEN_HERE"
```

#### **Update Role Permissions**
Dynamically sync permissions to a specific role.
```bash
curl -X PUT http://localhost:8000/api/admin/roles/ADMIN/permissions \
     -H "Authorization: Bearer OWNER_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -d '{
           "permissions": ["create_post", "edit_post"]
         }'
```

---

## 📂 Project Structure

- `app/Http/Controllers/Api`: RESTful API Controllers.
- `app/Http/Middleware`: RBAC checking logic (`CheckRole`, `CheckPermission`).
- `app/Models`: Core Domain Models (User, Role, Permission, Post).
- `database/seeders`: Initialization logic for the RBAC system.
- `routes/api.php`: The API entry point.

---

## 📜 License
MIT License. Feel free to use and modify for your own projects.
