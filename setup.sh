#!/bin/bash

echo "🚀 Setting up Laravel Docker Application..."

# Check if docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if docker-compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create necessary directories
mkdir -p docker/app
mkdir -p docker/postgres/init

# Copy configuration files if they don't exist
if [ ! -f docker/app/php.ini ]; then
    cp docker/app/php.ini.example docker/app/php.ini 2>/dev/null || echo "Please create docker/app/php.ini"
fi

# Build and start containers
echo "📦 Building and starting Docker containers..."
docker-compose down
docker-compose up --build -d

# Wait for PostgreSQL to be ready
echo "⏳ Waiting for PostgreSQL to be ready..."
while ! docker exec postgres_db pg_isready -U postgres -d laravel > /dev/null 2>&1; do
    sleep 2
done

echo "✅ PostgreSQL is ready!"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec app composer install --no-interaction --prefer-dist

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate --force

# Run database seeds if needed
# docker-compose exec app php artisan db:seed --force

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
docker-compose exec node npm install

# Build frontend assets
echo "🔨 Building frontend assets..."
docker-compose exec node npm run build

echo "🎉 Setup complete!"
echo "🌐 Your application is running at: http://localhost:8000"
echo "📊 Frontend dev server: http://localhost:5173"
echo "🗄️ PostgreSQL: localhost:5432"
echo "🔴 Redis: localhost:6379"

echo ""
echo "📝 Useful commands:"
echo "   docker-compose logs app    # View app logs"
echo "   docker-compose exec app bash  # Enter app container"
echo "   docker-compose down        # Stop all services"
