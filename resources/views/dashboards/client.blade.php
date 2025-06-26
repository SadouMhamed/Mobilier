@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Client') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Message de bienvenue -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">👋</div>
                        <div>
                            <h3 class="text-lg font-semibold">Bienvenue, {{ Auth::user()->name }} !</h3>
                            <p class="text-gray-600">Gérez vos annonces immobilières et demandes de services.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Publier une annonce -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-blue-600 mr-4">🏠</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Publier une annonce</h4>
                                <p class="text-sm text-gray-600">Créer une nouvelle annonce immobilière</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Créer une annonce
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Demander un service -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-green-600 mr-4">🔧</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Demander un service</h4>
                                <p class="text-sm text-gray-600">Faire appel à nos techniciens</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Demander un service
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mes annonces -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-purple-600 mr-4">📋</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Mes annonces</h4>
                                <p class="text-sm text-gray-600">Gérer mes propriétés</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('properties.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir mes annonces
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mes services actifs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-orange-600 mr-4">🛠️</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Mes demandes</h4>
                                <p class="text-sm text-gray-600">Suivre mes services actifs</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Demandes actives
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne des actions rapides -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
                <!-- Services terminés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-green-600 mr-4">✅</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Services terminés</h4>
                                <p class="text-sm text-gray-600">Consulter l'historique et évaluer</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.completed') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir l'historique
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Annonces publiques -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-indigo-600 mr-4">🌐</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Annonces publiques</h4>
                                <p class="text-sm text-gray-600">Parcourir toutes les annonces</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('properties.public') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir les annonces
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Annonces -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ Auth::user()->properties()->count() }}
                                </p>
                                <p class="text-gray-600">Annonces publiées</p>
                            </div>
                            <div class="text-3xl text-blue-600">🏠</div>
                        </div>
                    </div>
                </div>

                <!-- Demandes de service -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ Auth::user()->serviceRequests()->count() }}
                                </p>
                                <p class="text-gray-600">Demandes de service</p>
                            </div>
                            <div class="text-3xl text-green-600">🔧</div>
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous services -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ Auth::user()->appointments()->whereNotIn('status', ['termine', 'annule'])->count() }}
                                </p>
                                <p class="text-gray-600">RDV Services actifs</p>
                            </div>
                            <div class="text-3xl text-purple-600">🔧</div>
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous visites -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ Auth::user()->propertyAppointments()->count() }}
                                </p>
                                <p class="text-gray-600">RDV Visites</p>
                            </div>
                            <div class="text-3xl text-indigo-600">🏠</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mes rendez-vous -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Prochains rendez-vous -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">📅 Prochains rendez-vous</h4>
                            <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $upcomingAppointments = Auth::user()->appointments()
                                ->where('scheduled_at', '>', now())
                                ->whereNotIn('status', ['annule', 'termine'])
                                ->with('serviceRequest.service')
                                ->orderBy('scheduled_at')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($upcomingAppointments as $appointment)
                            <div class="border-l-4 border-blue-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->scheduled_at->format('d/m/Y à H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->scheduled_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun rendez-vous planifié</p>
                            <a href="{{ route('appointments.create') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                Planifier un rendez-vous →
                            </a>
                        @endforelse
                    </div>
                </div>

                <!-- Demandes en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🔧 Demandes en cours</h4>
                            <a href="{{ route('service-requests.index') }}" class="text-green-600 hover:text-green-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $activeRequests = Auth::user()->serviceRequests()
                                ->whereIn('status', ['en_attente', 'assignee', 'en_cours'])
                                ->with('service')
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($activeRequests as $request)
                            <div class="border-l-4 border-green-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $request->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($request->status == 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($request->status == 'assignee') bg-blue-100 text-blue-800
                                        @elseif($request->status == 'en_cours') bg-orange-100 text-orange-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </p>
                                <p class="text-xs text-gray-500">Créée le {{ $request->created_at->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucune demande en cours</p>
                            <a href="{{ route('service-requests.create') }}" class="inline-block mt-2 text-green-600 hover:text-green-800 text-sm">
                                Faire une demande →
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Nouvelle section : Rendez-vous de visite -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Prochaines visites -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🏠 Prochaines visites</h4>
                            <a href="{{ route('property-appointments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $upcomingVisits = Auth::user()->propertyAppointments()
                                ->whereIn('status', ['pending', 'confirmed'])
                                ->with('property')
                                ->orderBy('requested_date')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($upcomingVisits as $visit)
                            <div class="border-l-4 {{ $visit->status === 'pending' ? 'border-yellow-500' : 'border-indigo-500' }} pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $visit->property->title }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $visit->status === 'confirmed' && $visit->confirmed_date ? $visit->confirmed_date->format('d/m/Y à H:i') : $visit->requested_date->format('d/m/Y à H:i') }}
                                </p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $visit->status_color }}">
                                    {{ $visit->status_label }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucune visite planifiée</p>
                            <a href="{{ route('properties.public') }}" class="inline-block mt-2 text-indigo-600 hover:text-indigo-800 text-sm">
                                Parcourir les annonces →
                            </a>
                        @endforelse
                    </div>
                </div>

                <!-- Rendez-vous récents terminés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">✅ Rendez-vous récents</h4>
                            <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $recentCompletedAppointments = Auth::user()->appointments()
                                ->whereIn('status', ['termine'])
                                ->with('serviceRequest.service')
                                ->orderBy('completed_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($recentCompletedAppointments as $appointment)
                            <div class="border-l-4 border-gray-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $appointment->completed_at ? $appointment->completed_at->format('d/m/Y à H:i') : $appointment->scheduled_at->format('d/m/Y à H:i') }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Terminé
                                    </span>
                                    <p class="text-xs text-gray-500">{{ $appointment->completed_at ? $appointment->completed_at->diffForHumans() : 'Récemment' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun rendez-vous terminé récemment</p>
                            <a href="{{ route('service-requests.completed') }}" class="inline-block mt-2 text-gray-600 hover:text-gray-800 text-sm">
                                Voir les services terminés →
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 