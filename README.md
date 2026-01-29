# � Ultimate Laravel RBAC & Blog Management System

A high-performance, production-ready Laravel 12 backend architecture featuring **JWT-based Authentication**, a fully dynamic **Role-Based Access Control (RBAC)** system, and a secured **Content API**.

---

## 🚀 Overview

This project is built to handle complex authorization requirements where roles and permissions are not just hardcoded but are **database-driven** and **instantly dynamic**. Designed for seamless integration with modern frontend frameworks like **React.js**.

### ✨ Key Features
- **🛡️ Dynamic RBAC**: Change permissions in the database, and they take effect **immediately** without code changes.
- **🔐 JWT Authentication**: Stateless security using `tymon/jwt-auth` with blacklist support for secure logout.
- **👑 Owner Dashboard**: Specialized endpoints for the `OWNER` to manage users and redefined role capabilities on the fly.
- **📝 Blog API**: Fully secured CRUD operations for posts with granular permission enforcement.
- **⚡ Frontend Harmony**: Delivers a boolean `permissions` map in auth responses for effortless UI toggling.
- **✅ Robust Validation**: strictly typed inputs using Laravel Form Requests.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com/)
- **Authentication**: [JWT-Auth](https://github.com/tymondesigns/jwt-auth)
- **Database**: [PostgreSQL](https://www.postgresql.org/)
- **Language**: PHP 8.2+

---

## ⚙️ Installation & Quick Start

### 1. Clone & Install
```bash
git clone <repository-url>
cd role_managment_backend
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
# Update DB_DATABASE, DB_USERNAME, and DB_PASSWORD in .env
```

### 3. Database & Auth Keys
```bash
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
```

### 4. Run the Server
```bash
php artisan serve --port=8080
```

---

## 👥 Role & Permission Hierarchy

| Role | Level | Description |
| :--- | :--- | :--- |
| **OWNER** | 👑 Max | System creator. Full access. Immutable. |
| **SUPERADMIN**| 🛡️ High | Can manage all blog content (CRUD). |
| **ADMIN** | 📝 Mid | Can create and read content. |
| **USER** | 👥 Low | Default role. Can only read content. |

### 🔑 Core Permissions
- `post.read`: View blog posts.
- `post.create`: Publish new posts.
- `post.edit`: Modify existing posts.
- `post.delete`: Remove posts.

---

## 📚 API Guidelines & CURL Commands

### � Authentication API

#### **Register a New User**
Automatically assigns the `USER` role.
```bash
curl -X POST http://localhost:8000/api/register \
     -H "Content-Type: application/json" \
     -d '{"username": "johndoe", "password": "password123"}'
```

#### **Login**
Returns the **JWToken** and the **Permissions Map**.
```bash
curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"username": "owner", "password": "password"}'
```

#### **Get Profile (Me)**
```bash
curl -X GET http://localhost:8000/api/me \
     -H "Authorization: Bearer <YOUR_TOKEN>"
```

---

### 📝 Blog API (CRUD)

| Action | Endpoint | Permission |
| :--- | :--- | :--- |
| **List All** | `GET /api/posts` | `post.read` |
| **Create** | `POST /api/posts` | `post.create` |
| **Update** | `PUT /api/posts/{id}` | `post.edit` |
| **Delete** | `DELETE /api/posts/{id}` | `post.delete` |

---

### 👑 Owner Dashboard (Admin Endpoints)

#### **List All Users**
```bash
curl -X GET http://localhost:8000/api/admin/users \
     -H "Authorization: Bearer <OWNER_TOKEN>"
```

#### **Update User Role**
```bash
curl -X PUT http://localhost:8000/api/admin/users/2/role \
     -H "Authorization: Bearer <OWNER_TOKEN>" \
     -H "Content-Type: application/json" \
     -d '{"role": "SUPERADMIN"}'
```

#### **Manage Role Permissions**
Instantly sync permissions to a role.
```bash
curl -X PUT http://localhost:8000/api/admin/roles/3/permissions \
     -H "Authorization: Bearer <OWNER_TOKEN>" \
     -H "Content-Type: application/json" \
     -d '{"permissions": ["post.read", "post.create", "post.edit"]}'
```

---

## ⚛️ React.js Frontend Integration

The backend is designed to minimize frontend logic. On login, the `permissions` object provides a simple boolean map:

```json
"permissions": {
  "post.read": true,
  "post.create": true,
  "post.edit": false,
  "post.delete": false
}
```

**Frontend Suggestion (React):**
```javascript
{user.permissions['post.create'] && <CreatePostButton />}
```
This ensures that when the `OWNER` updates permissions in the backend, the UI updates **without a redeploy**.

---

## 📂 Project Structure

- `app/Http/Controllers/Api`: Clean, focused API controllers.
- `app/Http/Middleware`: `CheckRole` and `CheckPermission` guards.
- `app/Http/Requests`: Centralized validation logic.
- `database/seeders`: Initial RBAC state.

---

## 📜 License
MIT License - Developed with ❤️ for high-performance applications.
