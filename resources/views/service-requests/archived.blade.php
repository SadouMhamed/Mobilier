@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Services Archivés
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('service-requests.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Services Actifs
            </a>
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $archivedRequests->total() }}</div>
                        <div class="text-sm text-gray-600">Services Archivés</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $archivedRequests->where('status', 'terminee')->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Terminés</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $archivedRequests->unique('technicien_id')->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Techniciens Impliqués</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $archivedRequests->whereNotNull('final_notes')->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Avec Rapport Final</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des services archivés -->
        <div class="space-y-4">
            @forelse($archivedRequests as $serviceRequest)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $serviceRequest->service->name }}
                                    </h3>
                                    
                                    <!-- Badge statut -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Archivé
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
                                    <div>
                                        <strong>Client:</strong> {{ $serviceRequest->client->name }}
                                    </div>
                                    <div>
                                        <strong>Technicien:</strong> 
                                        <span class="text-blue-600 font-medium">{{ $serviceRequest->technicien->name ?? 'Non assigné' }}</span>
                                    </div>
                                    <div>
                                        <strong>Terminé le:</strong> {{ $serviceRequest->completed_at?->format('d/m/Y à H:i') }}
                                    </div>
                                    <div>
                                        <strong>Archivé le:</strong> {{ $serviceRequest->archived_at?->format('d/m/Y à H:i') }}
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600 mb-3">
                                    <strong>Description:</strong> {{ Str::limit($serviceRequest->description, 100) }}
                                </div>

                                <!-- Notes diverses -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    @if($serviceRequest->admin_notes)
                                    <div class="bg-blue-50 p-3 rounded">
                                        <h4 class="font-medium text-blue-800 text-sm">Notes Admin</h4>
                                        <p class="text-blue-700 text-sm mt-1">{{ $serviceRequest->admin_notes }}</p>
                                    </div>
                                    @endif

                                    @if($serviceRequest->technicien_notes)
                                    <div class="bg-green-50 p-3 rounded">
                                        <h4 class="font-medium text-green-800 text-sm">Notes Technicien</h4>
                                        <p class="text-green-700 text-sm mt-1">{{ $serviceRequest->technicien_notes }}</p>
                                    </div>
                                    @endif

                                    @if($serviceRequest->final_notes)
                                    <div class="bg-purple-50 p-3 rounded">
                                        <h4 class="font-medium text-purple-800 text-sm">Rapport Final</h4>
                                        <p class="text-purple-700 text-sm mt-1">{{ $serviceRequest->final_notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right ml-4">
                                <div class="text-lg font-bold text-green-600">
                                    {{ number_format($serviceRequest->service->price, 0, ',', ' ') }} DZD
                                </div>
                                <div class="text-sm text-gray-500">
                                    Durée: {{ $serviceRequest->completed_at?->diffInDays($serviceRequest->created_at) }} jours
                                </div>
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
                                    Télécharger PDF
                                </a>
                            </div>

                            <div class="text-sm text-gray-500">
                                Créé le {{ $serviceRequest->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun service archivé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Aucun service n'a encore été archivé.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($archivedRequests->hasPages())
            <div class="mt-8">
                {{ $archivedRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 