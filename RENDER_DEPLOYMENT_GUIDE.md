# Render Deployment Guide for Mobilier App

## 🚀 Quick Overview

Your Laravel app with React landing page and Blade/Tailwind dashboard will be deployed on Render with:

-   **Backend**: Laravel (PHP) with Blade views and API endpoints
-   **Frontend**: React landing page + Tailwind CSS
-   **Database**: Continue using your existing Supabase PostgreSQL
-   **Assets**: Vite build system for both React and Tailwind

## 📋 Pre-Deployment Checklist

### ✅ 1. Prepare Your Repository

Make sure your code is pushed to GitHub/GitLab:

```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

### ✅ 2. Environment Variables Ready

Have these values ready from your Supabase dashboard:

-   Database host, username, password
-   APP_KEY (generate new one for production)

## 🔧 Step-by-Step Deployment

### Step 1: Create Render Account

1. Go to [render.com](https://render.com)
2. Sign up with your GitHub/GitLab account
3. Connect your repository

### Step 2: Create New Web Service

1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub repository
3. Choose your `mobilier` repository
4. Configure the service:
    - **Name**: `mobilier-app`
    - **Environment**: `PHP`
    - **Build Command**: (see render.yaml - automatically configured)
    - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

### Step 3: Set Environment Variables

In your Render dashboard, go to **Environment** tab and add these variables:

#### 🔐 Required Environment Variables

```env
# Application Settings
APP_NAME="Mobilier App"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_NEW_APP_KEY_HERE
APP_URL=https://your-app-name.onrender.com

# Database (Your existing Supabase)
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=info

# Queue
QUEUE_CONNECTION=sync

# Broadcasting
BROADCAST_DRIVER=log

# Filesystem
FILESYSTEM_DISK=local

# Vite (for React/Tailwind builds)
VITE_APP_NAME="Mobilier App"
```

#### 🔑 Generate New APP_KEY

Run this locally and copy the result:

```bash
php artisan key:generate --show
```

### Step 4: Configure Build Settings

Render will automatically detect the `render.yaml` file, but verify these settings:

**Build Command:**

```bash
composer install --optimize-autoloader --no-dev && npm install && npm run build && php artisan key:generate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan storage:link
```

**Start Command:**

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Step 5: Deploy

1. Click **"Create Web Service"**
2. Render will start building your application
3. Monitor the build logs for any errors
4. Once deployed, you'll get a URL like: `https://mobilier-app.onrender.com`

## 🔧 Build Process Explanation

The deployment will:

1. **Install PHP dependencies** (`composer install`)
2. **Install Node.js dependencies** (`npm install`)
3. **Build React + Tailwind assets** (`npm run build`)
4. **Generate application key** for security
5. **Cache configurations** for performance
6. **Run database migrations** on your Supabase database
7. **Seed database** (if needed)
8. **Create storage links** for file uploads

## 🌐 Post-Deployment Verification

After deployment, test these URLs:

### ✅ Test Your Application

-   **Landing Page (React)**: `https://your-app.onrender.com/`
-   **Dashboard (Blade)**: `https://your-app.onrender.com/dashboard`
-   **Login**: `https://your-app.onrender.com/login`
-   **API endpoints**: `https://your-app.onrender.com/api/...`

### ✅ Check Database Connection

Your app should connect to the same Supabase database you've been using.

## 🔧 Troubleshooting Common Issues

### Build Failures

**PHP Extension Missing:**
Add to your `composer.json` if needed:

```json
{
    "require": {
        "ext-pdo": "*",
        "ext-pdo_pgsql": "*"
    }
}
```

**Node.js Build Issues:**
Ensure your `package.json` has the correct build script:

```json
{
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    }
}
```

**Migration Errors:**
Check your Supabase connection details in environment variables.

### Runtime Issues

**500 Internal Server Error:**

-   Check environment variables are set correctly
-   Verify APP_KEY is set
-   Check logs in Render dashboard

**Assets Not Loading:**

-   Ensure `npm run build` completed successfully
-   Check if Vite build created files in `public/build/`

**Database Connection:**

-   Verify Supabase credentials
-   Ensure your Supabase project allows connections from Render's IPs

## 🚀 Performance Optimizations

### Enable Caching

Your deployment automatically enables:

-   Configuration caching
-   Route caching
-   View caching

### Asset Optimization

-   Vite automatically minifies CSS/JS
-   Images are served from `public/` directory

## 🔒 Security Checklist

-   ✅ `APP_DEBUG=false` in production
-   ✅ Strong `APP_KEY` generated
-   ✅ Database credentials secured
-   ✅ HTTPS enabled (automatic on Render)
-   ✅ Environment variables not in code

## 📊 Monitoring & Maintenance

### Render Dashboard

-   Monitor build logs
-   Check application logs
-   View metrics and performance

### Supabase Dashboard

-   Monitor database performance
-   Check query logs
-   Manage backups

## 🔄 Updates & Redeployment

To update your application:

1. Push changes to your repository
2. Render automatically rebuilds and deploys
3. Or manually trigger deployment in Render dashboard

## 💰 Pricing Considerations

**Render Free Tier:**

-   750 hours/month free
-   App sleeps after 15 minutes of inactivity
-   Suitable for development/testing

**Render Paid Plans:**

-   $7/month for always-on service
-   Better performance and uptime
-   Recommended for production

## 🆘 Support Resources

-   **Render Documentation**: [render.com/docs](https://render.com/docs)
-   **Laravel Deployment**: [laravel.com/docs/deployment](https://laravel.com/docs/deployment)
-   **Supabase Docs**: [supabase.com/docs](https://supabase.com/docs)

## 📝 Environment Variables Template

Copy this template and fill in your values:

```env
APP_NAME="Mobilier App"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_NEW_KEY_HERE
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
LOG_CHANNEL=errorlog
LOG_LEVEL=info
QUEUE_CONNECTION=sync
BROADCAST_DRIVER=log
FILESYSTEM_DISK=local
VITE_APP_NAME="Mobilier App"
```
