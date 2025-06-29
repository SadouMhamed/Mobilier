<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mobilier Algérie') }} - Expert Immobilier en Algérie</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Expert immobilier en Algérie. Vente, achat, location de villas, appartements et terrains. Services techniques rapides. Alger, Oran, Constantine, Annaba.">
    <meta name="keywords" content="immobilier algérie, vente maison algérie, appartement alger, villa oran, terrain constantine, services techniques, gestion locative">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Mobilier Algérie">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Mobilier Algérie - Expert Immobilier en Algérie">
    <meta property="og:description" content="Rapidité, qualité et confiance pour tous vos projets immobiliers en Algérie. Découvrez notre sélection exclusive de biens.">
    <meta property="og:image" content="{{ url('/') }}/favicon.ico">
    <meta property="og:locale" content="fr_DZ">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Mobilier Algérie - Expert Immobilier">
    <meta property="twitter:description" content="Services immobiliers complets en Algérie. Vente, achat, location, services techniques.">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&family=poppins:400,500,600,700,800,900" rel="stylesheet" />
    
    <!-- Styles -->
    @if(app()->environment('local') || (app()->environment('production') && file_exists(public_path('build/manifest.json'))))
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @else
        <!-- Fallback CSS for production if Vite manifest is missing -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/tailwind.min.css" rel="stylesheet">
        <script>
            console.warn('Vite manifest not found, using fallback CSS');
        </script>
    @endif
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "RealEstateAgent",
      "name": "Mobilier Algérie",
      "description": "Expert immobilier en Algérie spécialisé dans la vente, l'achat et la location de biens immobiliers",
      "url": "{{ url('/') }}",
      "logo": "{{ url('/') }}/favicon.ico",
      "telephone": "+213-555-123-456",
      "email": "contact@mobilier-algerie.dz",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Cité Universitaire, Bab Ezzouar",
        "addressLocality": "Alger",
        "postalCode": "16000",
        "addressCountry": "DZ"
      },
      "areaServed": [
        {
          "@type": "State",
          "name": "Alger"
        },
        {
          "@type": "State", 
          "name": "Oran"
        },
        {
          "@type": "State",
          "name": "Constantine"
        },
        {
          "@type": "State",
          "name": "Annaba"
        }
      ],
      "serviceType": [
        "Vente immobilière",
        "Achat immobilier", 
        "Gestion locative",
        "Services techniques",
        "Évaluation immobilière"
      ]
    }
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            scroll-behavior: smooth;
        }
        
        #react-landing {
            min-height: 100vh;
        }
        
        /* Loading spinner avec style algérien */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            flex-direction: column;
        }
        
        .loading-logo {
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Smooth scrolling pour tous les navigateurs */
        html {
            scroll-behavior: smooth;
        }
        
        /* Optimisation des performances */
        img {
            content-visibility: auto;
        }
        
        /* Style pour les icônes loading */
        .loading-icon {
            width: 2rem;
            height: 2rem;
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div id="react-landing">
        <div class="loading">
            <div class="loading-logo">
                <svg class="loading-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Mobilier Algérie
            </div>
            <div class="spinner"></div>
            <p style="margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">Chargement de votre expert immobilier...</p>
        </div>
    </div>
</body>
</html>
