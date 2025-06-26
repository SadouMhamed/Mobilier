@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Services Terminés
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('service-requests.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Services Actifs
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('service-requests.archived') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Archives
            </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- En-tête avec statistiques -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $completedRequests->total() }}</div>
                        <div class="text-sm text-gray-600">Services Terminés</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $completedRequests->where('final_notes', '!=', null)->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Avec Rapport Final</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $completedRequests->where('technicien_notes', '!=', null)->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Avec Notes Technicien</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des services terminés -->
        <div class="space-y-4">
            @forelse($completedRequests as $serviceRequest)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $serviceRequest->service->name }}
                                    </h3>
                                    
                                    <!-- Badge statut -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Terminé
                                    </span>

                                    <!-- Badge de priorité -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($serviceRequest->priority == 'urgente') bg-red-100 text-red-800
                                        @elseif($serviceRequest->priority == 'haute') bg-orange-100 text-orange-800
                                        @elseif($serviceRequest->priority == 'normale') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($serviceRequest->priority) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600 mb-4">
                                    @if(auth()->user()->role !== 'client')
                                    <div>
                                        <strong>Client:</strong> {{ $serviceRequest->client->name }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>Technicien:</strong> 
                                        <span class="text-blue-600 font-medium">{{ $serviceRequest->technicien->name ?? 'Non assigné' }}</span>
                                    </div>
                                    <div>
                                        <strong>Terminé le:</strong> {{ $serviceRequest->completed_at?->format('d/m/Y à H:i') }}
                                    </div>
                                    <div>
                                        <strong>Durée totale:</strong> {{ $serviceRequest->completed_at?->diffInDays($serviceRequest->created_at) }} jours
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600 mb-3">
                                    <strong>Description:</strong> {{ Str::limit($serviceRequest->description, 100) }}
                                </div>

                                <!-- Notes et rapports -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
                                    @if($serviceRequest->admin_notes)
                                    <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-500">
                                        <h4 class="font-medium text-blue-800 text-sm mb-1">📝 Notes Admin</h4>
                                        <p class="text-blue-700 text-xs">{{ Str::limit($serviceRequest->admin_notes, 80) }}</p>
                                    </div>
                                    @endif

                                    @if($serviceRequest->technicien_notes)
                                    <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                        <h4 class="font-medium text-green-800 text-sm mb-1">🔧 Notes Technicien</h4>
                                        <p class="text-green-700 text-xs">{{ Str::limit($serviceRequest->technicien_notes, 80) }}</p>
                                    </div>
                                    @endif

                                    @if($serviceRequest->final_notes)
                                    <div class="bg-purple-50 p-3 rounded border-l-4 border-purple-500">
                                        <h4 class="font-medium text-purple-800 text-sm mb-1">📋 Rapport Final</h4>
                                        <p class="text-purple-700 text-xs">{{ Str::limit($serviceRequest->final_notes, 80) }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Évaluation du service (si applicable) -->
                                @if($serviceRequest->client_rating || $serviceRequest->client_feedback)
                                <div class="bg-yellow-50 p-3 rounded border-l-4 border-yellow-500 mt-3">
                                    <h4 class="font-medium text-yellow-800 text-sm mb-1">⭐ Évaluation Client</h4>
                                    @if($serviceRequest->client_rating)
                                        <div class="flex items-center mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-yellow-500">{{ $i <= $serviceRequest->client_rating ? '★' : '☆' }}</span>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $serviceRequest->client_rating }}/5)</span>
                                        </div>
                                    @endif
                                    @if($serviceRequest->client_feedback)
                                        <p class="text-yellow-700 text-xs">{{ $serviceRequest->client_feedback }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div class="text-right ml-4">
                                <div class="text-lg font-bold text-green-600">
                                    {{ number_format($serviceRequest->service->price, 0, ',', ' ') }} DZD
                                </div>
                                <div class="text-sm text-gray-500">
                                    Créé {{ $serviceRequest->created_at->diffForHumans() }}
                                </div>
                                @if($serviceRequest->is_archived)
                                <div class="text-xs text-gray-400 mt-1">
                                    📁 Archivé
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex space-x-2">
                                <a href="{{ route('service-requests.show', $serviceRequest) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>
                                <a href="{{ route('service-requests.pdf', $serviceRequest) }}" 
                                   class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    PDF
                                </a>
                                
                                @if(auth()->user()->role === 'client' && !$serviceRequest->client_rating && $serviceRequest->client_id === auth()->id())
                                <button onclick="openRatingModal({{ $serviceRequest->id }})" 
                                        class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                    Évaluer
                                </button>
                                @endif
                            </div>

                            <div class="text-sm text-gray-500">
                                #{{ $serviceRequest->id }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun service terminé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Aucun service n'a encore été terminé.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($completedRequests->hasPages())
            <div class="mt-8">
                {{ $completedRequests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal d'évaluation (pour les clients) -->
@if(auth()->user()->role === 'client')
<div id="ratingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Évaluer le service</h3>
            <form id="ratingForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note (1-5 étoiles)</label>
                    <div class="flex justify-center space-x-1" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" 
                                    class="star text-2xl text-gray-300 hover:text-yellow-500" data-rating="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="client_rating" id="ratingValue" required>
                </div>
                <div class="mb-4">
                    <label for="client_feedback" class="block text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                    <textarea name="client_feedback" id="client_feedback" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Votre avis sur le service..."></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeRatingModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md">
                        Évaluer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRating = 0;

function openRatingModal(serviceRequestId) {
    document.getElementById('ratingForm').action = `/service-requests/${serviceRequestId}/rate`;
    document.getElementById('ratingModal').classList.remove('hidden');
}

function closeRatingModal() {
    document.getElementById('ratingModal').classList.add('hidden');
    resetRating();
}

function setRating(rating) {
    currentRating = rating;
    document.getElementById('ratingValue').value = rating;
    
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-500');
        } else {
            star.classList.remove('text-yellow-500');
            star.classList.add('text-gray-300');
        }
    });
}

function resetRating() {
    currentRating = 0;
    document.getElementById('ratingValue').value = '';
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.classList.remove('text-yellow-500');
        star.classList.add('text-gray-300');
    });
}
</script>
@endif
@endsection 