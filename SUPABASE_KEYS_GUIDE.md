# 🔑 Supabase API Keys Guide

## 🎯 What Are These Keys For?

The Supabase API keys enable client-side features in your React components:

-   **`SUPABASE_URL`**: Your project's API endpoint
-   **`SUPABASE_ANON_KEY`**: Public key for client-side operations (safe to expose)
-   **`SUPABASE_SERVICE_ROLE_KEY`**: Admin key for server-side operations (keep secret!)

## 📍 Where to Find Your Keys

### Step 1: Go to Supabase Dashboard

1. Open [supabase.com](https://supabase.com)
2. Sign in to your account
3. Select your project

### Step 2: Navigate to Settings

1. Click on **Settings** (gear icon) in the sidebar
2. Go to **API** tab

### Step 3: Copy Your Keys

You'll see these values:

```
Project URL: https://your-project-ref.supabase.co
anon public: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
service_role: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

## 🔧 How to Use These Keys

### For Laravel Backend (Server-side)

You're already using the database connection, but you can also use the Supabase REST API:

```php
// In your Laravel controllers
$supabaseUrl = env('SUPABASE_URL');
$serviceKey = env('SUPABASE_SERVICE_ROLE_KEY');

// Make API calls to Supabase
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $serviceKey,
    'apikey' => $serviceKey,
])->get($supabaseUrl . '/rest/v1/your-table');
```

### For React Frontend (Client-side)

You can use the Supabase JavaScript client:

```javascript
// Install Supabase JS client
npm install @supabase/supabase-js

// In your React components
import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY

const supabase = createClient(supabaseUrl, supabaseAnonKey)

// Use for real-time subscriptions, direct queries, etc.
```

## 🔒 Security Best Practices

### ✅ Safe to Expose (Public)

-   `SUPABASE_URL` - Your project URL
-   `SUPABASE_ANON_KEY` - Designed to be public

### ⚠️ Keep Secret (Server-only)

-   `SUPABASE_SERVICE_ROLE_KEY` - Full admin access
-   Never use in client-side code
-   Only use in Laravel backend

## 🌐 Environment Variables Setup

### For Your .env File (Local Development)

```env
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### For Vite (React Frontend)

Add these to your .env for React to access:

```env
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### For Render Deployment

Add all these environment variables in your Render dashboard:

```env
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

## 🚀 Use Cases

### 1. Real-time Subscriptions

```javascript
// Listen to database changes in React
supabase
    .channel("public:appointments")
    .on(
        "postgres_changes",
        {
            event: "*",
            schema: "public",
            table: "appointments",
        },
        (payload) => {
            console.log("Change received!", payload);
        }
    )
    .subscribe();
```

### 2. Direct Database Queries from React

```javascript
// Query data directly from React components
const { data, error } = await supabase
    .from("properties")
    .select("*")
    .eq("status", "available");
```

### 3. File Upload to Supabase Storage

```javascript
// Upload files directly from React
const { data, error } = await supabase.storage
    .from("property-images")
    .upload("public/image.jpg", file);
```

### 4. Row Level Security (RLS)

With RLS enabled, the anon key respects user permissions:

```javascript
// Only returns data the authenticated user can see
const { data } = await supabase.from("user_properties").select("*");
```

## 🔄 When You Need These Keys

You need these keys if you want to:

-   ✅ Use Supabase real-time features
-   ✅ Make direct API calls from React
-   ✅ Use Supabase Storage for file uploads
-   ✅ Implement Row Level Security
-   ✅ Use Supabase Auth (if not using Laravel Auth)

You DON'T need them if you:

-   ❌ Only use Laravel Eloquent for database operations
-   ❌ Handle all data through Laravel controllers
-   ❌ Don't need real-time features

## 📝 Updated Environment Variables

Your complete environment variables should now include:

```env
# Application
APP_NAME=Mobilier App
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:etnxLIDiqysgXpnnCtZvVFsIQfWUxaigZWAliqJG+Qw=
APP_URL=https://your-app-name.onrender.com

# Database (PostgreSQL connection)
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Supabase API (for client-side features)
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=your-supabase-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-supabase-service-role-key

# Vite (for React to access Supabase)
VITE_SUPABASE_URL=https://your-project-ref.supabase.co
VITE_SUPABASE_ANON_KEY=your-supabase-anon-key

# Other settings
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
LOG_CHANNEL=errorlog
LOG_LEVEL=info
QUEUE_CONNECTION=sync
BROADCAST_DRIVER=log
FILESYSTEM_DISK=local
VITE_APP_NAME=Mobilier App
```

## 🎯 Next Steps

1. **Get your keys** from Supabase dashboard
2. **Add them to your .env** file locally
3. **Add them to Render** environment variables
4. **Install Supabase JS** if you want client-side features: `npm install @supabase/supabase-js`
5. **Test your deployment** with the new keys
