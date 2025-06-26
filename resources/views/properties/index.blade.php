<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ Auth::user()->role === 'admin' ? 'Gestion des Annonces' : 'Mes Annonces' }}
            </h2>
            @if(Auth::user()->role === 'client')
                <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Créer une annonce
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de succès -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filtres pour admin -->
            @if(Auth::user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('properties.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Toutes ({{ $properties->total() }})
                            </a>
                            <a href="{{ route('properties.index', ['status' => 'en_attente']) }}" class="px-4 py-2 bg-yellow-200 rounded-lg hover:bg-yellow-300">
                                En attente
                            </a>
                            <a href="{{ route('properties.index', ['status' => 'validee']) }}" class="px-4 py-2 bg-green-200 rounded-lg hover:bg-green-300">
                                Validées
                            </a>
                            <a href="{{ route('properties.index', ['status' => 'rejetee']) }}" class="px-4 py-2 bg-red-200 rounded-lg hover:bg-red-300">
                                Rejetées
                            </a>
                            <a href="{{ route('properties.index', ['status' => 'vendue']) }}" class="px-4 py-2 bg-purple-200 rounded-lg hover:bg-purple-300">
                                Vendues
                            </a>
                            <a href="{{ route('properties.index', ['status' => 'louee']) }}" class="px-4 py-2 bg-blue-200 rounded-lg hover:bg-blue-300">
                                Louées
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Liste des annonces -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $property)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                        <!-- Image -->
                        <div class="h-48 bg-gray-200 relative">
                            @if($property->images && count($property->images) > 0)
                                <img src="{{ asset('storage/' . $property->images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badge statut -->
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->status_color }}">
                                    {{ $property->status_label }}
                                </span>
                            </div>

                            <!-- Badge type -->
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $property->type_display }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $property->title }}</h3>
                                <div class="text-lg font-bold text-blue-600">
                                    {{ number_format($property->price, 0, ',', ' ') }} DZD
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-2">{{ ucfirst($property->property_type) }}</p>
                            <p class="text-gray-500 text-sm mb-3">{{ $property->city }} ({{ $property->postal_code }})</p>

                            <!-- Détails -->
                            <div class="flex items-center text-sm text-gray-500 space-x-4 mb-4">
                                @if($property->surface)
                                    <span>{{ $property->surface }}m²</span>
                                @endif
                                @if($property->rooms)
                                    <span>{{ $property->rooms }} pièces</span>
                                @endif
                                @if($property->bathrooms)
                                    <span>{{ $property->bathrooms }} SDB</span>
                                @endif
                            </div>

                            <!-- Propriétaire (pour admin) -->
                            @if(Auth::user()->role === 'admin')
                                <p class="text-sm text-gray-500 mb-3">
                                    Par: {{ $property->user->name }} ({{ $property->user->email }})
                                </p>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-4 border-t">
                                <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>

                                <div class="flex flex-wrap gap-1">
                                    @if(Auth::user()->role === 'admin')
                                        @if($property->status == 'en_attente')
                                            <form method="POST" action="{{ route('properties.validate', $property) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                    Valider
                                                </button>
                                            </form>
                                            <button onclick="openRejectModal({{ $property->id }})" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                                Rejeter
                                            </button>
                                        @elseif($property->isUnavailable())
                                            <form method="POST" action="{{ route('properties.reactivate', $property) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                    Remettre en ligne
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <!-- Boutons pour marquer comme vendu/loué -->
                                    @if(($property->user_id == Auth::id() || Auth::user()->role === 'admin') && $property->isAvailable())
                                        @if($property->type === 'vente')
                                            <form method="POST" action="{{ route('properties.mark-as-sold', $property) }}" class="inline" onsubmit="return confirm('Marquer cette propriété comme vendue ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded text-xs">
                                                    Vendu
                                                </button>
                                            </form>
                                        @elseif($property->type === 'location')
                                            <form method="POST" action="{{ route('properties.mark-as-rented', $property) }}" class="inline" onsubmit="return confirm('Marquer cette propriété comme louée ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                                    Loué
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if($property->user_id == Auth::id() || Auth::user()->role === 'admin')
                                        <a href="{{ route('properties.edit', $property) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                            Modifier
                                        </a>
                                        <form method="POST" action="{{ route('properties.destroy', $property) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune annonce</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(Auth::user()->role === 'client')
                                    Commencez par créer votre première annonce.
                                @else
                                    Aucune annonce n'a été soumise pour le moment.
                                @endif
                            </p>
                            @if(Auth::user()->role === 'client')
                                <div class="mt-6">
                                    <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Créer une annonce
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($properties->hasPages())
                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de rejet (pour admin) -->
    @if(Auth::user()->role === 'admin')
        <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter l'annonce</h3>
                    <form id="rejectForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="admin_comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Motif du rejet *
                            </label>
                            <textarea id="admin_comment" name="admin_comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" required placeholder="Expliquez pourquoi cette annonce est rejetée..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRejectModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Rejeter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openRejectModal(propertyId) {
                document.getElementById('rejectForm').action = `/properties/${propertyId}/reject`;
                document.getElementById('rejectModal').classList.remove('hidden');
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
                document.getElementById('admin_comment').value = '';
            }
        </script>
    @endif
</x-app-layout> 