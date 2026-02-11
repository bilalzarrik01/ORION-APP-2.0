# Odin Deployment Guide (Linux)

This guide targets Ubuntu 22.04/24.04 with Nginx, PHP-FPM, MySQL, and optional queue worker.

## 1. Server Prerequisites

Update packages:

```bash
sudo apt update && sudo apt upgrade -y
```

Install runtime dependencies:

```bash
sudo apt install -y nginx mysql-server unzip git curl supervisor
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl
```

Install Composer:

```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

Install Node.js 20 LTS:

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

## 2. MySQL Setup

```bash
sudo mysql
```

Run SQL commands:

```sql
CREATE DATABASE odin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'odin_user'@'localhost' IDENTIFIED BY 'change_this_password';
GRANT ALL PRIVILEGES ON odin.* TO 'odin_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 3. Application Setup

```bash
sudo mkdir -p /var/www
cd /var/www
sudo git clone <your-repo-url> odin
sudo chown -R $USER:$USER /var/www/odin
cd /var/www/odin/ORION-APP
```

Install PHP dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

Create env file:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for production:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=odin
DB_USERNAME=odin_user
DB_PASSWORD=change_this_password

CACHE_STORE=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Run DB setup:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Build frontend assets:

```bash
npm ci
npm run build
```

Optimize and link storage:

```bash
php artisan storage:link
php artisan optimize
```

Permissions:

```bash
sudo chown -R www-data:www-data /var/www/odin/ORION-APP/storage /var/www/odin/ORION-APP/bootstrap/cache
sudo chmod -R 775 /var/www/odin/ORION-APP/storage /var/www/odin/ORION-APP/bootstrap/cache
```

## 4. Nginx Configuration

Copy provided config:

```bash
sudo cp deploy/nginx/odin.conf /etc/nginx/sites-available/odin
sudo ln -s /etc/nginx/sites-available/odin /etc/nginx/sites-enabled/odin
sudo nginx -t
sudo systemctl reload nginx
```

Update in config:
- `server_name your-domain.com`
- PHP socket path if needed (`php8.3-fpm.sock`)

## 5. Queue Worker (for notifications/events if queued)

Create queue table once:

```bash
php artisan queue:table
php artisan migrate --force
```

Supervisor config `/etc/supervisor/conf.d/odin-worker.conf`:

```ini
[program:odin-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/odin/ORION-APP/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/odin/ORION-APP/storage/logs/worker.log
stopwaitsecs=3600
```

Enable it:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start odin-worker:*
```

## 6. HTTPS with Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

## 7. Deployment Update Flow

```bash
cd /var/www/odin/ORION-APP
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
sudo systemctl reload php8.3-fpm
sudo systemctl reload nginx
```

## 8. Health Checks

```bash
php artisan about
php artisan route:list
tail -f storage/logs/laravel.log
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
```
