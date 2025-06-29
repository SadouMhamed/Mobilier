# Supabase Setup Guide for Laravel

## Step 1: Create Supabase Project

1. Go to [supabase.com](https://supabase.com)
2. Sign up or log in
3. Click "New Project"
4. Choose your organization
5. Fill in project details:
    - Name: `mobilier-app` (or your preferred name)
    - Database Password: Create a strong password (save it!)
    - Region: Choose closest to your users
6. Click "Create new project"
7. Wait for project to be created (takes 1-2 minutes)

## Step 2: Get Database Credentials

Once your project is ready:

1. Go to **Settings** → **Database**
2. Scroll down to **Connection info**
3. You'll see these details (copy them):
    - Host: `db.xxxxxxxxxxxxxx.supabase.co`
    - Database name: `postgres`
    - Port: `5432`
    - User: `postgres`
    - Password: (the one you created)

## Step 3: Update Your .env File

Open your `.env` file and update the database configuration:

```env
# Change from SQLite to PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_database_password_here
```

**Replace the following:**

-   `db.xxxxxxxxxxxxxx.supabase.co` with your actual Supabase host
-   `your_database_password_here` with your actual database password

## Step 4: Test the Connection

Run this command to test if Laravel can connect to Supabase:

```bash
php artisan migrate:status
```

If you get an error, double-check your credentials.

## Step 5: Run Migrations

Once the connection works, run your migrations:

```bash
# Run all migrations
php artisan migrate

# If you want to start fresh (this will drop all tables first)
php artisan migrate:fresh

# Run migrations and seed data
php artisan migrate:fresh --seed
```

## Step 6: Optional - Supabase Dashboard Features

Your Supabase project comes with additional features:

### Database Browser

-   Go to **Table Editor** in Supabase dashboard
-   You can view, edit, and manage your data directly
-   Create tables, add columns, etc.

### SQL Editor

-   Go to **SQL Editor**
-   Run custom SQL queries
-   Great for debugging and data analysis

### API Auto-generation

-   Supabase automatically creates REST APIs for your tables
-   Go to **API** section to see auto-generated endpoints
-   You can use these alongside Laravel's Eloquent

### Real-time Features

-   Supabase supports real-time subscriptions
-   Useful for live updates in your application

## Step 7: Backup Your Local Data (If Needed)

If you have important data in your current SQLite database:

```bash
# Export data from SQLite (if you have important data)
php artisan db:seed --class=YourDataSeeder

# Or manually export specific tables
```

## Step 8: Update Your Production Environment

When you deploy to production, make sure to:

1. Set the same environment variables on your hosting platform
2. Run migrations: `php artisan migrate --force`
3. Cache configuration: `php artisan config:cache`

## Common Issues and Solutions

### DNS Resolution Error ("could not translate host name")

If you get this error, it means the hostname cannot be found:

1. **Double-check your hostname** in Supabase dashboard:

    - Go to Settings → Database
    - Copy the exact hostname from "Connection info"
    - Make sure there are no extra spaces or characters

2. **Verify your project is ready**:

    - Wait 2-3 minutes after creating the project
    - The database might still be initializing

3. **Test the connection**:

    ```bash
    # Test if hostname resolves
    ping your-hostname.supabase.co

    # Clear config cache after updating .env
    php artisan config:clear
    ```

4. **Alternative connection method**:
   If the hostname doesn't work, try using the direct connection string:
    ```env
    DB_URL=postgresql://postgres:your-password@db.your-project.supabase.co:5432/postgres
    ```

### Connection Refused Error

-   Check if your IP is allowed (Supabase allows all IPs by default)
-   Verify host, port, username, and password

### SSL Connection Error

Add this to your .env if you get SSL errors:

```env
DB_SSLMODE=require
```

### Migration Errors

If you get migration errors:

```bash
# Clear config cache
php artisan config:clear

# Try migrating again
php artisan migrate
```

## Useful Commands for Supabase

```bash
# Check database connection
php artisan tinker
# Then run: DB::connection()->getPdo();

# View all tables
php artisan db:table --show

# Run specific seeder
php artisan db:seed --class=ServiceSeeder

# Rollback migrations if needed
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

## Security Best Practices

1. **Never commit your .env file** - It contains sensitive credentials
2. **Use environment variables** in production
3. **Regular backups** - Supabase has automated backups, but consider additional backups
4. **Row Level Security** - Enable RLS in Supabase for additional security
5. **Database roles** - Create specific roles for different environments

## Next Steps After Setup

1. Test all your existing functionality
2. Verify that your models work correctly with PostgreSQL
3. Update any SQLite-specific queries if needed
4. Consider using Supabase's additional features like Storage for file uploads
5. Set up monitoring and alerts in Supabase dashboard

## Support

If you encounter issues:

-   Check Supabase documentation: [supabase.com/docs](https://supabase.com/docs)
-   Laravel database documentation: [laravel.com/docs/database](https://laravel.com/docs/database)
-   Supabase community: [github.com/supabase/supabase/discussions](https://github.com/supabase/supabase/discussions)
