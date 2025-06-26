@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Évaluations des clients
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('service-requests.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Retour aux services
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistiques générales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $evaluatedServices->count() }}
                            </p>
                            <p class="text-gray-600">Services évalués</p>
                        </div>
                        <div class="text-3xl text-green-600">📝</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ number_format($averageRating, 1) }}
                            </p>
                            <p class="text-gray-600">Note moyenne</p>
                        </div>
                        <div class="text-3xl text-yellow-600">⭐</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $excellentRatings }}
                            </p>
                            <p class="text-gray-600">Notes 5/5</p>
                        </div>
                        <div class="text-3xl text-blue-600">🌟</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-red-600">
                                {{ $lowRatings }}
                            </p>
                            <p class="text-gray-600">Notes ≤ 2/5</p>
                        </div>
                        <div class="text-3xl text-red-600">⚠️</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="technicien_filter" class="block text-sm font-medium text-gray-700 mb-1">Technicien</label>
                        <select name="technicien_id" id="technicien_filter" class="block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Tous les techniciens</option>
                            @foreach($techniciens as $technicien)
                                <option value="{{ $technicien->id }}" {{ request('technicien_id') == $technicien->id ? 'selected' : '' }}>
                                    {{ $technicien->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="rating_filter" class="block text-sm font-medium text-gray-700 mb-1">Note minimum</label>
                        <select name="min_rating" id="rating_filter" class="block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes les notes</option>
                            <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                            <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ étoiles</option>
                            <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ étoiles</option>
                            <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ étoiles</option>
                            <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>1+ étoiles</option>
                        </select>
                    </div>

                    <div>
                        <label for="service_filter" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" id="service_filter" class="block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Tous les services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des évaluations -->
        <div class="space-y-4">
            @forelse($evaluatedServices as $serviceRequest)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 
                    {{ $serviceRequest->client_rating >= 4 ? 'border-green-500' : '' }}
                    {{ $serviceRequest->client_rating == 3 ? 'border-yellow-500' : '' }}
                    {{ $serviceRequest->client_rating <= 2 ? 'border-red-500' : '' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $serviceRequest->service->name }}
                                    </h3>
                                    
                                    <!-- Note avec étoiles -->
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-lg {{ $i <= $serviceRequest->client_rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-600">
                                            ({{ $serviceRequest->client_rating }}/5)
                                        </span>
                                    </div>

                                    <!-- Badge de statut -->
                                    @if($serviceRequest->client_rating >= 4)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Excellent
                                        </span>
                                    @elseif($serviceRequest->client_rating == 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Correct
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            À améliorer
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                    <div>
                                        <strong>Client:</strong> {{ $serviceRequest->client->name }}
                                        <br>
                                        <span class="text-xs">{{ $serviceRequest->client->email }}</span>
                                    </div>
                                    <div>
                                        <strong>Technicien:</strong> 
                                        @if($serviceRequest->technicien)
                                            {{ $serviceRequest->technicien->name }}
                                            <br>
                                            <span class="text-xs">{{ $serviceRequest->technicien->speciality }}</span>
                                        @else
                                            <span class="text-gray-400">Non assigné</span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>Date de service:</strong> 
                                        <br>
                                        <span class="text-xs">{{ $serviceRequest->completed_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>

                                @if($serviceRequest->client_feedback)
                                <div class="bg-gray-50 p-4 rounded border-l-4 border-gray-300 mb-3">
                                    <h4 class="font-medium text-gray-800 text-sm mb-2">💬 Commentaire du client</h4>
                                    <p class="text-gray-700 text-sm">{{ $serviceRequest->client_feedback }}</p>
                                </div>
                                @endif
                            </div>

                                                            <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-blue-600">
                                        {{ number_format($serviceRequest->service->price, 0, ',', ' ') }} DZD
                                    </div>
                                <div class="text-xs text-gray-500">
                                    #{{ $serviceRequest->id }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex space-x-2">
                                <a href="{{ route('service-requests.show', $serviceRequest) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>
                                
                                @if($serviceRequest->client_rating <= 2)
                                    <span class="text-red-600 text-sm font-medium">
                                        ⚠️ Suivi requis
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-500">
                                Évalué {{ $serviceRequest->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune évaluation trouvée</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Aucun service n'a encore été évalué.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($evaluatedServices->hasPages())
            <div class="mt-8">
                {{ $evaluatedServices->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Analyse rapide -->
        @if($evaluatedServices->count() > 0)
        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">📈 Analyse rapide</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Répartition des notes -->
                    <div>
                        <h5 class="font-medium text-gray-800 mb-3">Répartition des notes</h5>
                        @php
                            $ratingCounts = $evaluatedServices->groupBy('client_rating')->map->count();
                        @endphp
                        @for($i = 5; $i >= 1; $i--)
                            @php $count = $ratingCounts->get($i, 0); @endphp
                            <div class="flex items-center mb-2">
                                <span class="w-8 text-sm">{{ $i }}★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-4 mx-3">
                                    <div class="bg-yellow-500 h-4 rounded-full" 
                                         style="width: {{ $evaluatedServices->count() > 0 ? ($count / $evaluatedServices->count()) * 100 : 0 }}%"></div>
                                </div>
                                <span class="w-8 text-sm text-gray-600">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>

                    <!-- Top techniciens -->
                    <div>
                        <h5 class="font-medium text-gray-800 mb-3">Techniciens les mieux notés</h5>
                        @php
                            $topTechniciens = $evaluatedServices->whereNotNull('technicien')->groupBy('technicien.id')
                                ->map(function($services) {
                                    return [
                                        'name' => $services->first()->technicien->name,
                                        'average' => $services->avg('client_rating'),
                                        'count' => $services->count()
                                    ];
                                })->sortByDesc('average')->take(5);
                        @endphp
                        @foreach($topTechniciens as $tech)
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm">{{ $tech['name'] }}</span>
                                <div class="flex items-center">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span class="text-sm font-medium">{{ number_format($tech['average'], 1) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $tech['count'] }})</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 