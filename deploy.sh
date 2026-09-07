#!/bin/bash
set -euo pipefail

# TaskCheck Deployment Script
# This script helps deploy the TaskCheck application to production

echo "🚀 Starting TaskCheck deployment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel project root."
    exit 1
fi

# Pull latest changes (if using git)
if [ -d ".git" ]; then
    echo "📥 Pulling latest changes..."
    git pull origin main
fi

# Plesk Git actions may not include Node.js in PATH.
export PATH="/opt/plesk/node/24/bin:/opt/plesk/node/22/bin:$PATH"

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm ci --include=dev

# Build assets
echo "🎨 Building assets..."
npm run build

# Clear and cache config
echo "⚙️ Optimizing configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Set proper permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear application cache
echo "🧹 Clearing application cache..."
php artisan cache:clear
php artisan view:clear

# Restart queue workers (if using queues)
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting queue workers..."
    php artisan queue:restart
fi

echo "✅ Deployment completed successfully!"
echo ""
echo "🌐 Your TaskCheck application is ready!"
echo "📝 Don't forget to:"
echo "   - Update your .env file with production settings"
echo "   - Configure your web server (Apache/Nginx)"
echo "   - Set up SSL certificate"
echo "   - Configure backups"
echo "   - Test the application thoroughly"
echo ""
echo "🎉 Happy task managing!"