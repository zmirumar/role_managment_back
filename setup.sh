#!/bin/bash

# Laravel JWT Authentication Backend - Setup Script
# This script helps you set up the Laravel backend quickly

echo "=========================================="
echo "Laravel JWT Authentication Backend Setup"
echo "=========================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env first"
    exit 1
fi

echo "📋 Step 1: Checking PostgreSQL configuration..."
echo ""

# Read database credentials from .env
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)

if [ -z "$DB_PASSWORD" ]; then
    echo "⚠️  Warning: DB_PASSWORD is empty in .env file"
    echo ""
    echo "Please update your .env file with PostgreSQL credentials:"
    echo "  DB_CONNECTION=pgsql"
    echo "  DB_HOST=127.0.0.1"
    echo "  DB_PORT=5432"
    echo "  DB_DATABASE=role_management"
    echo "  DB_USERNAME=your_postgres_username"
    echo "  DB_PASSWORD=your_postgres_password"
    echo ""
    read -p "Have you configured your database credentials? (y/n) " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Please configure your database and run this script again."
        exit 1
    fi
fi

echo "✅ Database configuration found"
echo ""

echo "📋 Step 2: Testing database connection..."
php artisan db:show 2>/dev/null
if [ $? -ne 0 ]; then
    echo "⚠️  Could not connect to database. Please check your credentials."
    echo ""
    read -p "Do you want to continue anyway? (y/n) " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo "✅ Database connection successful"
fi
echo ""

echo "📋 Step 3: Running database migrations..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo "❌ Migration failed. Please check your database configuration."
    exit 1
fi
echo "✅ Migrations completed successfully"
echo ""

echo "📋 Step 4: Clearing cache..."
php artisan config:clear
php artisan cache:clear
echo "✅ Cache cleared"
echo ""

echo "=========================================="
echo "✅ Setup Complete!"
echo "=========================================="
echo ""
echo "🚀 To start the development server, run:"
echo "   php artisan serve"
echo ""
echo "📚 API Endpoints:"
echo "   POST   /api/register  - Register new user"
echo "   POST   /api/login     - Login user"
echo "   GET    /api/me        - Get current user (protected)"
echo "   POST   /api/logout    - Logout (protected)"
echo "   GET    /api/users     - Get all users (protected)"
echo ""
echo "📖 For detailed documentation, see README.md"
echo ""
