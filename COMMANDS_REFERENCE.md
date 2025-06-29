# Laravel Commands Reference

## Artisan Commands

### Database Commands

```bash
php artisan migrate
```

**Explanation:** Runs all pending database migrations
**Why use it:** To update your database schema with new tables, columns, or modifications

```bash
php artisan migrate:rollback
```

**Explanation:** Rolls back the last batch of migrations
**Why use it:** To undo recent database changes if something went wrong

```bash
php artisan migrate:fresh
```

**Explanation:** Drops all tables and re-runs all migrations
**Why use it:** To completely reset your database during development

```bash
php artisan migrate:fresh --seed
```

**Explanation:** Drops all tables, re-runs migrations, and runs seeders
**Why use it:** To reset database and populate it with test data

```bash
php artisan db:seed
```

**Explanation:** Runs the database seeders
**Why use it:** To populate your database with sample or initial data

```bash
php artisan make:migration create_table_name
```

**Explanation:** Creates a new migration file
**Why use it:** To create new database tables or modify existing ones

```bash
php artisan make:seeder TableSeeder
```

**Explanation:** Creates a new seeder class
**Why use it:** To create sample data for your database tables

### Model Commands

```bash
php artisan make:model ModelName
```

**Explanation:** Creates a new Eloquent model
**Why use it:** To create a new model for database interaction

```bash
php artisan make:model ModelName -m
```

**Explanation:** Creates a model with its migration file
**Why use it:** To create both model and migration at the same time

```bash
php artisan make:model ModelName -mcr
```

**Explanation:** Creates model with migration, controller, and resource routes
**Why use it:** To quickly scaffold a complete resource (model, controller, migration)

### Controller Commands

```bash
php artisan make:controller ControllerName
```

**Explanation:** Creates a new controller class
**Why use it:** To handle HTTP requests and application logic

```bash
php artisan make:controller ControllerName --resource
```

**Explanation:** Creates a controller with resource methods (index, create, store, show, edit, update, destroy)
**Why use it:** To create a full CRUD controller quickly

### Request Commands

```bash
php artisan make:request RequestName
```

**Explanation:** Creates a form request class for validation
**Why use it:** To handle form validation and authorization logic

### Middleware Commands

```bash
php artisan make:middleware MiddlewareName
```

**Explanation:** Creates a new middleware class
**Why use it:** To filter HTTP requests entering your application

### Cache Commands

```bash
php artisan cache:clear
```

**Explanation:** Clears the application cache
**Why use it:** To remove cached data when you make changes

```bash
php artisan config:cache
```

**Explanation:** Creates a cache file for faster configuration loading
**Why use it:** To improve performance in production

```bash
php artisan config:clear
```

**Explanation:** Removes the configuration cache file
**Why use it:** To clear cached config when making configuration changes

```bash
php artisan route:cache
```

**Explanation:** Creates a route cache file for faster route registration
**Why use it:** To improve performance in production

```bash
php artisan route:clear
```

**Explanation:** Removes the route cache file
**Why use it:** To clear route cache when adding new routes

```bash
php artisan view:cache
```

**Explanation:** Compiles all Blade templates
**Why use it:** To improve performance by pre-compiling views

```bash
php artisan view:clear
```

**Explanation:** Clears all compiled view files
**Why use it:** To force recompilation of Blade templates after changes

### Queue Commands

```bash
php artisan queue:work
```

**Explanation:** Starts processing jobs on the queue
**Why use it:** To process background jobs like sending emails

```bash
php artisan queue:listen
```

**Explanation:** Listens to a given queue and processes jobs
**Why use it:** Alternative to queue:work that automatically reloads when code changes

```bash
php artisan make:job JobName
```

**Explanation:** Creates a new job class
**Why use it:** To create background tasks that can be queued

### Storage Commands

```bash
php artisan storage:link
```

**Explanation:** Creates a symbolic link from public/storage to storage/app/public
**Why use it:** To make uploaded files accessible via web browser

### Route Commands

```bash
php artisan route:list
```

**Explanation:** Lists all registered routes
**Why use it:** To see all available routes in your application

### Maintenance Commands

```bash
php artisan down
```

**Explanation:** Puts the application in maintenance mode
**Why use it:** To display a maintenance page during updates

```bash
php artisan up
```

**Explanation:** Brings the application out of maintenance mode
**Why use it:** To restore normal operation after maintenance

### Optimization Commands

```bash
php artisan optimize
```

**Explanation:** Caches the framework bootstrap files
**Why use it:** To improve application performance

```bash
php artisan optimize:clear
```

**Explanation:** Removes the cached bootstrap files
**Why use it:** To clear all optimization caches at once

## Composer Commands

```bash
composer install
```

**Explanation:** Installs all dependencies listed in composer.json
**Why use it:** To set up the project after cloning or when dependencies change

```bash
composer update
```

**Explanation:** Updates all dependencies to their latest versions
**Why use it:** To get the latest versions of packages

```bash
composer require package/name
```

**Explanation:** Adds a new package to your project
**Why use it:** To install new PHP packages

```bash
composer require --dev package/name
```

**Explanation:** Adds a package as a development dependency
**Why use it:** To install packages only needed during development

```bash
composer dump-autoload
```

**Explanation:** Regenerates the autoloader files
**Why use it:** When you add new classes or change namespaces

## NPM Commands

```bash
npm install
```

**Explanation:** Installs all JavaScript dependencies
**Why use it:** To set up frontend dependencies after cloning

```bash
npm run dev
```

**Explanation:** Compiles assets for development
**Why use it:** To build CSS and JavaScript files during development

```bash
npm run build
```

**Explanation:** Compiles and minifies assets for production
**Why use it:** To prepare assets for production deployment

```bash
npm run watch
```

**Explanation:** Watches for file changes and recompiles automatically
**Why use it:** To automatically rebuild assets during development

## Git Commands (Common in Laravel Projects)

```bash
git add .
```

**Explanation:** Stages all changes for commit
**Why use it:** To prepare changes for committing

```bash
git commit -m "commit message"
```

**Explanation:** Commits staged changes with a message
**Why use it:** To save changes to the repository

```bash
git push origin branch-name
```

**Explanation:** Pushes commits to remote repository
**Why use it:** To share your changes with others

```bash
git pull origin branch-name
```

**Explanation:** Fetches and merges changes from remote repository
**Why use it:** To get the latest changes from other developers

```bash
git status
```

**Explanation:** Shows the status of working directory and staging area
**Why use it:** To see what files have been modified

```bash
git log --oneline
```

**Explanation:** Shows commit history in a compact format
**Why use it:** To see recent commits and their messages

## Server Commands

```bash
php artisan serve
```

**Explanation:** Starts the Laravel development server
**Why use it:** To run your application locally during development

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

**Explanation:** Starts server accessible from any IP on port 8000
**Why use it:** To make your local server accessible to other devices on the network

## Testing Commands

```bash
php artisan test
```

**Explanation:** Runs all tests using Pest/PHPUnit
**Why use it:** To verify your application works correctly

```bash
php artisan make:test TestName
```

**Explanation:** Creates a new test class
**Why use it:** To create tests for your application features

## Environment Commands

```bash
php artisan key:generate
```

**Explanation:** Generates a new application encryption key
**Why use it:** To set up the APP_KEY in your .env file

```bash
php artisan env:encrypt
```

**Explanation:** Encrypts the environment file
**Why use it:** To secure your environment configuration

## Tinker (Laravel REPL)

```bash
php artisan tinker
```

**Explanation:** Opens an interactive PHP shell with Laravel loaded
**Why use it:** To test code, query database, or debug interactively

## Custom Commands for Your Project

Based on your project structure, here are some specific commands you might use:

```bash
# Create a new service request
php artisan make:model ServiceRequest -mcr

# Create a new appointment
php artisan make:model Appointment -mcr

# Seed your database with services
php artisan db:seed --class=ServiceSeeder

# Clear all caches when making changes
php artisan optimize:clear

# Create a new middleware for role checking
php artisan make:middleware CheckRole
```

## Daily Development Workflow Commands

```bash
# Start your development day
php artisan serve
npm run watch

# After making changes
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Before committing
php artisan test
git add .
git commit -m "Your commit message"
git push origin main

# Database updates
php artisan migrate
php artisan db:seed
```

## Supabase Database Commands

```bash
php artisan migrate:status
```

**Explanation:** Shows the status of all migrations
**Why use it:** To check if your Supabase database connection is working and see which migrations have run

```bash
php artisan migrate:fresh --seed
```

**Explanation:** Drops all tables, re-runs all migrations, and seeds data
**Why use it:** To reset your Supabase database with fresh data during development

```bash
php artisan tinker
```

**Explanation:** Opens Laravel's interactive shell
**Why use it:** To test database connections and run queries directly: `DB::connection()->getPdo();`

```bash
php artisan config:clear
```

**Explanation:** Clears cached configuration
**Why use it:** After updating .env file with Supabase credentials, clear config cache

```bash
php artisan db:seed --class=ServiceSeeder
```

**Explanation:** Runs a specific seeder class
**Why use it:** To populate your Supabase database with specific data

## Production Deployment Commands

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Build production assets
npm run build

# Run migrations on production
php artisan migrate --force
```

## Render Deployment Commands

```bash
# Generate new APP_KEY for production
php artisan key:generate --show
```

**Explanation:** Generates a new application encryption key for production
**Why use it:** To create a secure key for your production environment

```bash
# Test build process locally
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**Explanation:** Simulates the Render build process locally
**Why use it:** To test if your build will work on Render before deploying

```bash
# Prepare for deployment
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

**Explanation:** Commits and pushes all changes to trigger Render deployment
**Why use it:** Render automatically deploys when you push to your main branch

```bash
# Check if assets built correctly
ls -la public/build/
```

**Explanation:** Lists the built assets in the public directory
**Why use it:** To verify that Vite successfully built your React and Tailwind assets
