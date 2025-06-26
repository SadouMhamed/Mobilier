@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Administrateur') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Message de bienvenue -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">👨‍💼</div>
                        <div>
                            <h3 class="text-lg font-semibold">Bienvenue, {{ Auth::user()->name }} !</h3>
                            <p class="text-gray-600">Gérez les annonces, services et utilisateurs de la plateforme.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Annonces en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-yellow-600 mr-4">⏳</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Annonces en attente</h4>
                                <p class="text-sm text-gray-600">À valider/rejeter</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('properties.index', ['status' => 'en_attente']) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Gérer les annonces
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-orange-600 mr-4">🔧</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Services en attente</h4>
                                <p class="text-sm text-gray-600">Demandes à assigner</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.index', ['status' => 'en_attente']) }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Assigner techniciens
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Toutes les annonces -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-blue-600 mr-4">🏠</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Toutes les annonces</h4>
                                <p class="text-sm text-gray-600">Gestion complète</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('properties.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir les annonces
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tous les services -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-green-600 mr-4">🛠️</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Tous les services</h4>
                                <p class="text-sm text-gray-600">Gestion complète</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir les services
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services archivés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-gray-600 mr-4">📁</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Services archivés</h4>
                                <p class="text-sm text-gray-600">Services terminés</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.archived') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir les archives
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Évaluations clients -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-yellow-500 mr-4">📊</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Évaluations clients</h4>
                                <p class="text-sm text-gray-600">Notes et commentaires</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.evaluations') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir les évaluations
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total annonces -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ \App\Models\Property::count() }}
                                </p>
                                <p class="text-gray-600">Annonces totales</p>
                            </div>
                            <div class="text-3xl text-blue-600">🏠</div>
                        </div>
                    </div>
                </div>

                <!-- Annonces en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{ \App\Models\Property::where('status', 'en_attente')->count() }}
                                </p>
                                <p class="text-gray-600">En attente validation</p>
                            </div>
                            <div class="text-3xl text-yellow-600">⏳</div>
                        </div>
                    </div>
                </div>

                <!-- Services en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-orange-600">
                                    {{ \App\Models\ServiceRequest::where('status', 'en_attente')->count() }}
                                </p>
                                <p class="text-gray-600">Services à assigner</p>
                            </div>
                            <div class="text-3xl text-orange-600">🔧</div>
                        </div>
                    </div>
                </div>

                <!-- Total techniciens -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\User::where('role', 'technicien')->where('is_active', true)->count() }}
                                </p>
                                <p class="text-gray-600">Techniciens actifs</p>
                            </div>
                            <div class="text-3xl text-green-600">👷‍♂️</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableaux de bord -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Annonces récentes en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🏠 Annonces en attente de validation</h4>
                            <a href="{{ route('properties.index', ['status' => 'en_attente']) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $pendingProperties = \App\Models\Property::where('status', 'en_attente')
                                ->with('user')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($pendingProperties as $property)
                            <div class="border-l-4 border-yellow-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                <p class="text-sm text-gray-600">Par {{ $property->user->name }} - {{ number_format($property->price, 0, ',', ' ') }} DZD</p>
                                <p class="text-xs text-gray-500">Créée {{ $property->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucune annonce en attente</p>
                        @endforelse
                    </div>
                </div>

                <!-- Services récents en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🔧 Services à assigner</h4>
                            <a href="{{ route('service-requests.index', ['status' => 'en_attente']) }}" class="text-orange-600 hover:text-orange-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $pendingServices = \App\Models\ServiceRequest::where('status', 'en_attente')
                                ->with(['client', 'service'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($pendingServices as $serviceRequest)
                            <div class="border-l-4 border-orange-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">Par {{ $serviceRequest->client->name }} - {{ $serviceRequest->city }}</p>
                                <p class="text-xs text-gray-500">
                                    Créée {{ $serviceRequest->created_at->diffForHumans() }}
                                    @if($serviceRequest->priority === 'urgente')
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            🔴 Urgent
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun service en attente</p>
                        @endforelse
                    </div>
                </div>

                <!-- Services en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">⚙️ Services en cours</h4>
                            <a href="{{ route('service-requests.index', ['status' => 'en_cours']) }}" class="text-green-600 hover:text-green-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $activeServices = \App\Models\ServiceRequest::where('status', 'en_cours')
                                ->with(['client', 'service', 'technicien'])
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($activeServices as $serviceRequest)
                            <div class="border-l-4 border-green-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Client: {{ $serviceRequest->client->name }} | 
                                    Technicien: {{ $serviceRequest->technicien?->name ?? 'Non assigné' }}
                                </p>
                                <p class="text-xs text-gray-500">Démarré {{ $serviceRequest->started_at?->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun service en cours</p>
                        @endforelse
                    </div>
                </div>

                <!-- Rendez-vous services du jour -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🔧 RDV Services aujourd'hui</h4>
                            <a href="{{ route('appointments.index') }}" class="text-purple-600 hover:text-purple-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $todayAppointments = \App\Models\Appointment::whereDate('scheduled_at', today())
                                ->with(['client', 'serviceRequest.service', 'serviceRequest.technicien'])
                                ->orderBy('scheduled_at')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($todayAppointments as $appointment)
                            <div class="border-l-4 border-purple-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $appointment->scheduled_at->format('H:i') }} - 
                                    {{ $appointment->client->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Technicien: {{ $appointment->serviceRequest->technicien?->name ?? 'Non assigné' }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun RDV service aujourd'hui</p>
                        @endforelse
                    </div>
                </div>

                <!-- Visites en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🏠 Visites en attente</h4>
                            <a href="{{ route('property-appointments.pending') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $pendingVisits = \App\Models\PropertyAppointment::where('status', 'pending')
                                ->with(['client', 'property'])
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($pendingVisits as $visit)
                            <div class="border-l-4 border-yellow-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $visit->property->title }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $visit->client_name }} - {{ $visit->requested_date->format('d/m/Y à H:i') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Demandé {{ $visit->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucune visite en attente</p>
                        @endforelse
                    </div>
                </div>

                <!-- Visites confirmées aujourd'hui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🏠 Visites aujourd'hui</h4>
                            <a href="{{ route('property-appointments.today') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $todayVisits = \App\Models\PropertyAppointment::where('status', 'confirmed')
                                ->whereDate('confirmed_date', today())
                                ->with(['client', 'property'])
                                ->orderBy('confirmed_date')
                                ->limit(3)
                                ->get();
                        @endphp

                        @forelse($todayVisits as $visit)
                            <div class="border-l-4 border-blue-500 pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $visit->property->title }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $visit->confirmed_date->format('H:i') }} - {{ $visit->client_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $visit->property->city }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucune visite aujourd'hui</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 