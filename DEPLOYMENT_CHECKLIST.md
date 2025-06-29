# 🚀 Render Deployment Checklist

## ✅ Pre-Deployment Steps

### 1. Repository Ready

-   [ ] All code committed and pushed to GitHub/GitLab
-   [ ] `render.yaml` file in root directory
-   [ ] `package.json` has correct build scripts

### 2. Environment Variables Ready

Copy these exact values to your Render dashboard:

```env
APP_NAME=Mobilier App
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:etnxLIDiqysgXpnnCtZvVFsIQfWUxaigZWAliqJG+Qw=
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
VITE_APP_NAME=Mobilier App

# Supabase API Keys (for client-side features)
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=your-supabase-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-supabase-service-role-key

# Vite variables (for React to access Supabase)
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=your-supabase-anon-key
```

### 3. Supabase Information Needed

From your Supabase dashboard (Settings → Database):

-   [ ] Database Host: `db.xxxxxx.supabase.co`
-   [ ] Database Password: `your-password`
-   [ ] Verify connection is working locally

## 🔧 Render Setup Steps

### Step 1: Create Render Account

-   [ ] Go to [render.com](https://render.com)
-   [ ] Sign up with GitHub/GitLab
-   [ ] Connect your repository

### Step 2: Create Web Service

-   [ ] Click "New +" → "Web Service"
-   [ ] Select your `mobilier` repository
-   [ ] Name: `mobilier-app`
-   [ ] Environment: `PHP`
-   [ ] Build Command: (auto-detected from render.yaml)
-   [ ] Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`

### Step 3: Environment Variables

-   [ ] Copy all environment variables from above
-   [ ] Replace `your-supabase-host.supabase.co` with actual host
-   [ ] Replace `your-supabase-password` with actual password
-   [ ] Update `APP_URL` with your actual Render URL

### Step 4: Deploy

-   [ ] Click "Create Web Service"
-   [ ] Monitor build logs
-   [ ] Wait for deployment to complete

## ✅ Post-Deployment Testing

### Test These URLs:

-   [ ] Landing Page (React): `https://your-app.onrender.com/`
-   [ ] Login Page: `https://your-app.onrender.com/login`
-   [ ] Dashboard: `https://your-app.onrender.com/dashboard`
-   [ ] Register: `https://your-app.onrender.com/register`

### Test Functionality:

-   [ ] User registration works
-   [ ] User login works
-   [ ] Dashboard loads correctly
-   [ ] Database operations work
-   [ ] React components load properly
-   [ ] Tailwind styles applied correctly

## 🔧 If Something Goes Wrong

### Check Build Logs

-   [ ] PHP dependencies installed correctly
-   [ ] Node.js dependencies installed
-   [ ] `npm run build` completed successfully
-   [ ] Migrations ran without errors

### Check Runtime Logs

-   [ ] No 500 errors
-   [ ] Database connection successful
-   [ ] Assets loading properly

### Common Fixes:

-   [ ] Verify all environment variables are set
-   [ ] Check Supabase connection details
-   [ ] Ensure APP_KEY is properly set
-   [ ] Verify APP_URL matches your Render URL

## 🎯 Success Criteria

Your deployment is successful when:

-   ✅ Build completes without errors
-   ✅ Application starts successfully
-   ✅ Landing page loads (React components work)
-   ✅ Login/registration works
-   ✅ Dashboard loads with proper styling
-   ✅ Database operations work
-   ✅ No console errors

## 📝 Notes

-   **Free Tier**: App sleeps after 15 minutes of inactivity
-   **Cold Starts**: First request after sleep takes 30-60 seconds
-   **Logs**: Available in Render dashboard
-   **Updates**: Push to GitHub triggers automatic redeployment

## 🆘 Need Help?

If you encounter issues:

1. Check Render build/runtime logs
2. Verify environment variables
3. Test Supabase connection
4. Check the troubleshooting section in `RENDER_DEPLOYMENT_GUIDE.md`
