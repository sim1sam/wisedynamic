# WiseDynamic Laravel Application - Deployment Guide

## Overview
This guide covers deploying the WiseDynamic Laravel application to a production server, specifically for deployment to `https://wisedynamic.com.bd/` where files are uploaded to `public_html/`.

## Deployment Structure
When deploying to a shared hosting environment where files go to `public_html/`, you have two options:

### Option 1: Root Directory Deployment (Recommended)
Upload all Laravel files to `public_html/` and use the provided `.htaccess` and `index.php` files in the root to handle routing.

### Option 2: Subdirectory Deployment
Create a subdirectory in `public_html/` and upload files there, then configure your domain to point to that directory.

## Pre-Deployment Checklist

### 1. Server Requirements
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)
- SSL certificate (recommended)

### 2. Files to Upload
Upload all project files to your server's web directory, ensuring:
- All vendor dependencies are included (run `composer install --no-dev` locally first)
- The `storage` and `bootstrap/cache` directories are writable
- The `.env` file is properly configured for production

## Deployment Steps

### Option 1: Using Deployment Scripts (Recommended)

#### For Windows Servers:
1. Upload the project files to your server
2. Double-click `deploy.bat` to run the deployment script
3. Follow the on-screen instructions

#### For Linux/Unix Servers:
1. Upload the project files to your server
2. Make the script executable: `chmod +x deploy.sh`
3. Run the script: `./deploy.sh`

### Option 2: Manual Deployment (If scripts don't work)

#### Step 1: Clear All Caches
```bash
php artisan optimize:clear
```

#### Step 2: Cache for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 3: Run Database Migrations
```bash
php artisan migrate --force
```

#### Step 4: Create Storage Link
```bash
php artisan storage:link
```

## Environment Configuration

### 1. Copy Environment File
- Copy `.env.production.example` to `.env`
- Update all configuration values for your production environment

### 2. Critical Environment Variables

#### Application Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

#### SSL Commerz (Payment Gateway)
```env
SSLCOMMERZ_STORE_ID=your_live_store_id
SSLCOMMERZ_STORE_PASSWORD=your_live_store_password
SSLCOMMERZ_SANDBOX=false
```

## File Permissions

### Linux/Unix Servers
Set proper file permissions:
```bash
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Windows Servers
Ensure the web server has read/write access to:
- `storage/` directory and all subdirectories
- `bootstrap/cache/` directory

## Web Server Configuration

### Apache (.htaccess)
The project includes a `.htaccess` file in the public directory. Ensure:
- `mod_rewrite` is enabled
- `AllowOverride All` is set for the directory

### Nginx
Add this configuration to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

## SSL Commerz Configuration

### Development vs Production
- **Development**: `SSLCOMMERZ_SANDBOX=true`
- **Production**: `SSLCOMMERZ_SANDBOX=false`

### Required Credentials
1. Obtain live credentials from SSL Commerz:
   - Store ID
   - Store Password
2. Update your `.env` file with these credentials
3. Test with a small transaction first

## Post-Deployment Testing

### 1. Basic Functionality
- [ ] Homepage loads correctly
- [ ] User registration/login works
- [ ] Admin panel is accessible
- [ ] Database connections are working

### 2. Payment System
- [ ] SSL Commerz integration works
- [ ] Test transactions (start with small amounts)
- [ ] Payment success/failure callbacks work
- [ ] Email notifications are sent

### 3. File Operations
- [ ] Image uploads work
- [ ] File downloads work
- [ ] Storage links are functional

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
- Check file permissions
- Verify `.env` configuration
- Check server error logs
- Ensure all caches are cleared

#### 2. Database Connection Issues
- Verify database credentials
- Check database server status
- Ensure database exists and is accessible

#### 3. SSL Commerz Payment Issues
- Verify sandbox mode is disabled for production
- Check store credentials
- Ensure callback URLs are accessible
- Test with SSL Commerz's validation tools

#### 4. File Permission Issues
- Set proper permissions for storage and cache directories
- Ensure web server can write to required directories

### Log Files
Check these log files for errors:
- `storage/logs/laravel.log`
- Web server error logs
- Database error logs

## Security Considerations

### 1. Environment File
- Never commit `.env` to version control
- Ensure `.env` is not accessible via web browser
- Use strong, unique passwords

### 2. File Permissions
- Don't set 777 permissions
- Use minimal required permissions
- Regularly audit file permissions

### 3. SSL Certificate
- Use HTTPS in production
- Ensure SSL certificate is valid
- Configure proper redirects

## Maintenance

### Regular Tasks
- Monitor log files for errors
- Keep Laravel and dependencies updated
- Regular database backups
- Monitor server resources

### Cache Management
When making changes to configuration or routes:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Support

For technical support or deployment assistance, please contact the development team with:
- Server specifications
- Error messages (if any)
- Steps already attempted
- Log file contents (relevant portions)

---

**Last Updated**: January 2025
**Version**: 1.0