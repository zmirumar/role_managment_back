# 🚀 Professional Laravel RBAC & Blog Backend

A production-ready Laravel 12 backend featuring **JWT Authentication**, a dynamic **database-driven Role & Permission system**, and a secured **Blog API**.

---

## 🌟 Core Features

- **🔐 Stateless Auth**: Full JWT authentication (Register, Login, Logout, Me).
- **🛡️ Dynamic RBAC**: Roles and permissions are stored in the database and checked at runtime.
- **👑 Owner Control**: A specialized `OWNER` role that can manage users, roles, and permissions dynamically.
- **📝 Blog Management**: Secure CRUD for posts with granular permission enforcement.
- **⚡ Frontend Ready**: Auth responses include a boolean permission map for immediate UI adjustments.
- **✅ Modern Validation**: All inputs are validated using Laravel Form Requests.

---

## 🛠️ Technical Stack

- **Framework**: Laravel 12.x
- **Auth**: `tymon/jwt-auth`
- **Database**: PostgreSQL
- **Middleware**: Custom `role` and `permission` filters.

---

## ⚙️ Installation

### 1. Requirements
- PHP 8.2+
- Composer
- PostgreSQL

### 2. Setup
```bash
# Install dependencies
composer install

# Configure environment
cp .env.example .env
# Set DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env

# Generate keys
php artisan key:generate
php artisan jwt:secret

# Initialize database
php artisan migrate:fresh --seed
```

---

## 👥 Roles & Permissions

### Initial Roles
| Role | Level | Default Permissions |
| :--- | :--- | :--- |
| `OWNER` | Max | Full access to everything (Immutable). |
| `SUPERADMIN` | High | `post.read`, `post.create`, `post.edit`, `post.delete` |
| `ADMIN` | Mid | `post.read`, `post.create` |
| `USER` | Low | `post.read` |

### Dynamic Permissions
Permissions are slugs: `post.read`, `post.create`, `post.edit`, `post.delete`.
Changes to `role_permissions` in the database take effect **immediately**.

---

## 📚 API Documentation

### 🔓 Public / Auth API

#### **Register**
`POST /api/register`
Default role assigned: `USER`.
```bash
curl -X POST http://localhost:8000/api/register \
     -H "Content-Type: application/json" \
     -d '{"username": "newuser", "password": "password"}'
```

#### **Login**
`POST /api/login`
Returns user, permissions map, and token.
```bash
curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"username": "owner", "password": "password"}'
```

#### **Get Auth User**
`GET /api/me`
```bash
curl -X GET http://localhost:8000/api/me \
     -H "Authorization: Bearer <TOKEN>"
```

---

### 📝 Blog API

| Method | Endpoint | Permission Required |
| :--- | :--- | :--- |
| `GET` | `/api/posts` | `post.read` |
| `POST` | `/api/posts` | `post.create` |
| `PUT` | `/api/posts/{id}` | `post.edit` |
| `DELETE` | `/api/posts/{id}` | `post.delete` |

---

### 👑 Owner Dashboard (Admin Only)

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/admin/users` | List all users. |
| `PUT` | `/api/admin/users/{id}/role` | Change user role. |
| `GET` | `/api/admin/roles` | List all roles & permissions. |
| `PUT` | `/api/admin/roles/{id}/permissions` | Update role permissions. |

---

## 📄 Response Format (Auth)

When you login or call `/me`, the backend returns:
```json
{
  "user": {
    "id": 1,
    "username": "owner",
    "role": "OWNER"
  },
  "permissions": {
    "post.read": true,
    "post.create": true,
    "post.edit": true,
    "post.delete": true
  },
  "token": "..."
}
```
The `permissions` object is a dynamic map. Frontend should use this to toggle UI elements.

---

## 📜 License
MIT
