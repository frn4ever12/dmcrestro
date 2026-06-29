# Deployment Guide

## Docker Deployment

### Prerequisites
- Docker
- Docker Compose

### Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd nepal-restaurant-saas
```

2. **Configure environment**
```bash
cp .env.example .env
```

Update the following variables in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=nepal_restaurant_saas
DB_USERNAME=nepal_user
DB_PASSWORD=nepal_password

REDIS_HOST=redis
REDIS_PASSWORD=null

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

3. **Build and start containers**
```bash
docker-compose up -d --build
```

4. **Install dependencies**
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

5. **Generate application key**
```bash
docker-compose exec app php artisan key:generate
```

6. **Run migrations**
```bash
docker-compose exec app php artisan migrate
```

7. **Seed database**
```bash
docker-compose exec app php artisan db:seed
```

8. **Build assets**
```bash
docker-compose exec app npm run build
```

9. **Set permissions**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

10. **Access the application**
- API: http://localhost:8000/api
- Health check: http://localhost:8000/up

### Docker Commands

**View logs**
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql
```

**Stop containers**
```bash
docker-compose down
```

**Restart containers**
```bash
docker-compose restart
```

**Execute commands in container**
```bash
docker-compose exec app php artisan <command>
docker-compose exec app composer <command>
docker-compose exec app npm <command>
```

## Production Deployment

### Server Requirements
- Ubuntu 20.04+ or similar
- PHP 8.3+
- MySQL 8.0+
- Nginx
- Redis
- Supervisor (for queue workers)
- SSL Certificate

### Steps

1. **Install dependencies**
```bash
sudo apt update
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-zip php8.3-gd php8.3-curl nginx mysql-server redis-server supervisor
```

2. **Configure Nginx**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/nepal-restaurant-saas/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

3. **Configure SSL (Let's Encrypt)**
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

4. **Setup Supervisor for Queue Worker**
```ini
[program:nepal-restaurant-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/nepal-restaurant-saas/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/nepal-restaurant-saas/storage/logs/queue-worker.log
stopwaitsecs=3600
```

5. **Setup Cron Job**
```bash
crontab -e
```
Add:
```
* * * * * cd /var/www/nepal-restaurant-saas && php artisan schedule:run >> /dev/null 2>&1
```

6. **Optimize for Production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
npm run build
```

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nepal_restaurant_saas
DB_USERNAME=secure_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Payment Gateways
ESEWA_MERCHANT_CODE=your_code
KHALTI_PUBLIC_KEY=your_key
KHALTI_SECRET_KEY=your_secret
```

### Backup Strategy

**Database Backup**
```bash
# Create backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p nepal_restaurant_saas > /backups/nepal_restaurant_$DATE.sql
```

**Add to cron for daily backups**
```
0 2 * * * /path/to/backup-script.sh
```

### Monitoring

**Monitor queue workers**
```bash
sudo supervisorctl status
```

**Monitor logs**
```bash
tail -f storage/logs/laravel.log
tail -f storage/logs/queue-worker.log
```

**Monitor disk space**
```bash
df -h
```

### Security Checklist

- [ ] Change default passwords
- [ ] Enable SSL/HTTPS
- [ ] Configure firewall (ufw)
- [ ] Disable root SSH login
- [ ] Set up fail2ban
- [ ] Regular security updates
- [ ] Backup strategy in place
- [ ] Monitor logs regularly
- [ ] Use strong passwords for database
- [ ] Restrict file permissions
- [ ] Enable 2FA for admin accounts
