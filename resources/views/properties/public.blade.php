<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Annonces Immobilières</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <!-- Navigation publique -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('properties.public') }}" class="flex items-center">
                        <span class="text-xl font-bold text-gray-900">🏠 Mobilier</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">S'inscrire</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Mon compte</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Déconnexion</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold mb-4">Annonces Immobilières</h1>
            <p class="text-xl text-blue-100">Trouvez le bien de vos rêves</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('properties.public') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tous</option>
                        <option value="vente" {{ request('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                        <option value="location" {{ request('type') == 'location' ? 'selected' : '' }}>Location</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de bien</label>
                    <select name="property_type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tous</option>
                        <option value="appartement" {{ request('property_type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="maison" {{ request('property_type') == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="studio" {{ request('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="bureau" {{ request('property_type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                        <option value="terrain" {{ request('property_type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                        <option value="local" {{ request('property_type') == 'local' ? 'selected' : '' }}>Local</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" name="city" value="{{ request('city') }}" placeholder="Ex: Paris" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix max (DZD)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Ex: 50000000" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="mb-6">
            <p class="text-gray-600">{{ $properties->total() }} annonce(s) trouvée(s)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($properties as $property)
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-lg transition-shadow">
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 rounded-t-lg relative overflow-hidden">
                        @if($property->images && count($property->images) > 0)
                            <img src="{{ asset('storage/' . $property->images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badge type -->
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                {{ ucfirst($property->type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $property->title }}</h3>
                            <div class="text-xl font-bold text-blue-600">
                                {{ number_format($property->price, 0, ',', ' ') }} DZD
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-2">{{ ucfirst($property->property_type) }}</p>
                        <p class="text-gray-500 text-sm mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $property->city }} ({{ $property->postal_code }})
                        </p>

                        <!-- Détails -->
                        <div class="flex items-center text-sm text-gray-500 space-x-4 mb-4">
                            @if($property->surface)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                    {{ $property->surface }}m²
                                </span>
                            @endif
                            @if($property->rooms)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $property->rooms }} pièces
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($property->description, 100) }}</p>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir détails →
                            </a>
                            
                            @auth
                                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Contacter
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Se connecter
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune annonce trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="mt-8">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</body>
</html> 