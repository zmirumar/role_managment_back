# Laravel RBAC & Blog Backend

A professional Laravel backend featuring JWT Authentication, a robust Role-Based Access Control (RBAC) system, and a full-featured Blog management system.

## Features

### 🔐 Authentication & Authorization
- **JWT Authentication**: Secure, stateless authentication using `tymon/jwt-auth`.
- **RBAC System**:
    - **Roles**: `USER`, `ADMIN`, `SUPERADMIN`, `OWNER`.
    - **Permissions**: Granular control via permissions (e.g., `create_post`, `edit_post`, `delete_post`).
    - **Dynamic Management**: OWNER can dynamically update role permissions via the API.
- **Middleware**: Custom `role` and `permission` middleware for route protection.

### 📝 Blog System
- **CRUD Operations**: Secure endpoints for managing blog posts.
- **Permission Enforcement**: Actions are automatically restricted based on the user's role and assigned permissions.

### 🛠️ Admin Dashboard
- **User Management**: OWNER can list all users and change their roles.
- **Role/Permission Management**: OWNER can view roles and manage the permission mappings.

## Tech Stack
- **Framework**: Laravel 12
- **Auth**: JWT (tymon/jwt-auth)
- **Database**: PostgreSQL
- **Language**: PHP 8.2+

---

## Setup Instructions

### 1. Database Configuration
Update your `.env` file with PostgreSQL credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=role_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Run Migrations & Seeders
This is critical to populate the initial roles and permissions.
```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

### 3. Generate JWT Secret
```bash
php artisan jwt:secret
```

---

## API Documentation

### Public Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register (default role: USER) |
| POST | `/api/login` | Login and receive JWT token |

### Blog Endpoints (Protected)
| Method | Endpoint | Permission Required |
|--------|----------|---------------------|
| GET | `/api/posts` | None (Publicly Viewable) |
| POST | `/api/posts` | `create_post` |
| PUT | `/api/posts/{id}` | `edit_post` |
| DELETE | `/api/posts/{id}` | `delete_post` |

### Admin Endpoints (OWNER Only)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/users` | List all users and their roles |
| PUT | `/api/admin/users/{id}/role` | Change a user's role |
| GET | `/api/admin/roles` | List all roles and permissions |
| PUT | `/api/admin/roles/{role}/permissions` | Update permissions for a role |

---

## Role Definitions

| Role | Description | Default Permissions |
|------|-------------|---------------------|
| **USER** | Standard customer | None (Read-only blog) |
| **ADMIN** | Content creator | `create_post` |
| **SUPERADMIN** | Senior editor | `create_post`, `edit_post`, `delete_post` |
| **OWNER** | System administrator| Bypasses all checks / Admin Dashboard |

---

## Security Highlights
- **Stateless Auth**: No sessions, perfect for mobile and SPAs.
- **Role Isolation**: Middleware ensures users only access what they are authorized for.
- **Input Sanitization**: All requests are validated using FormRequests.
- **Clean Structure**: Separation of concerns between Controllers, Models, and Resources.

## License
MIT
