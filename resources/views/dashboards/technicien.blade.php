@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Technicien') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Message de bienvenue -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">👷‍♂️</div>
                        <div>
                            <h3 class="text-lg font-semibold">Bienvenue, {{ Auth::user()->name }} !</h3>
                            <p class="text-gray-600">
                                Gérez vos interventions et rendez-vous.
                                @if(Auth::user()->speciality)
                                    Spécialité : {{ Auth::user()->speciality }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Mes interventions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-blue-600 mr-4">🔧</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Mes interventions</h4>
                                <p class="text-sm text-gray-600">Services assignés</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Voir mes services
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mes rendez-vous -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-green-600 mr-4">📅</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Mes rendez-vous</h4>
                                <p class="text-sm text-gray-600">Planning des RDV</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Mon planning
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-orange-600 mr-4">⚙️</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">En cours</h4>
                                <p class="text-sm text-gray-600">Services actifs</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('service-requests.index') }}?status=en_cours" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Services actifs
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Planifier RDV -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-purple-600 mr-4">📋</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Planifier RDV</h4>
                                <p class="text-sm text-gray-600">Nouveau rendez-vous</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('appointments.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded w-full inline-block text-center">
                                Planifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total services assignés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ Auth::user()->assignedServiceRequests()->count() }}
                                </p>
                                <p class="text-gray-600">Services assignés</p>
                            </div>
                            <div class="text-3xl text-blue-600">🔧</div>
                        </div>
                    </div>
                </div>

                <!-- Services en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-orange-600">
                                    {{ Auth::user()->assignedServiceRequests()->where('status', 'en_cours')->count() }}
                                </p>
                                <p class="text-gray-600">En cours</p>
                            </div>
                            <div class="text-3xl text-orange-600">⚙️</div>
                        </div>
                    </div>
                </div>

                <!-- Services terminés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ Auth::user()->assignedServiceRequests()->where('status', 'terminee')->count() }}
                                </p>
                                <p class="text-gray-600">Terminés</p>
                            </div>
                            <div class="text-3xl text-green-600">✅</div>
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-purple-600">
                                    @php
                                        $totalAppointments = \App\Models\Appointment::whereHas('serviceRequest', function($query) {
                                            $query->where('technicien_id', Auth::id());
                                        })->count();
                                    @endphp
                                    {{ $totalAppointments }}
                                </p>
                                <p class="text-gray-600">Rendez-vous total</p>
                            </div>
                            <div class="text-3xl text-purple-600">📅</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planning et tâches -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Prochains rendez-vous -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">📅 Prochains rendez-vous</h4>
                            <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
                        </div>
                        
                        @php
                            $upcomingAppointments = \App\Models\Appointment::whereHas('serviceRequest', function($query) {
                                $query->where('technicien_id', Auth::id());
                            })
                            ->where('scheduled_at', '>', now())
                            ->where('status', '!=', 'annule')
                            ->with(['client', 'serviceRequest.service'])
                            ->orderBy('scheduled_at')
                            ->limit(5)
                            ->get();
                        @endphp

                        @forelse($upcomingAppointments as $appointment)
                            <div class="border-l-4 
                                @if($appointment->status === 'planifie') border-blue-500
                                @elseif($appointment->status === 'confirme') border-green-500
                                @else border-gray-500 @endif
                                pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $appointment->scheduled_at->format('d/m/Y à H:i') }} - 
                                    {{ $appointment->client->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $appointment->scheduled_at->diffForHumans() }}
                                    @if($appointment->status === 'planifie')
                                        <span class="ml-2 text-blue-600">À confirmer</span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun rendez-vous planifié</p>
                            <a href="{{ route('appointments.create') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                Planifier un rendez-vous →
                            </a>
                        @endforelse
                    </div>
                </div>

                <!-- Services assignés récents -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">🔧 Mes services assignés</h4>
                            <div class="flex space-x-2">
                                <a href="{{ route('service-requests.index') }}" class="text-orange-600 hover:text-orange-800 text-sm">Actifs</a>
                                <span class="text-gray-400">|</span>
                                <a href="{{ route('service-requests.completed') }}" class="text-green-600 hover:text-green-800 text-sm">Terminés</a>
                            </div>
                        </div>
                        
                        @php
                            $myServices = Auth::user()->assignedServiceRequests()
                                ->whereIn('status', ['assignee', 'en_cours'])
                                ->with(['client', 'service'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($myServices as $serviceRequest)
                            <div class="border-l-4 
                                @if($serviceRequest->status === 'assignee') border-blue-500
                                @elseif($serviceRequest->status === 'en_cours') border-orange-500
                                @else border-gray-500 @endif
                                pl-4 mb-3 last:mb-0">
                                <p class="font-medium text-gray-900">{{ $serviceRequest->service->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Client: {{ $serviceRequest->client->name }} - {{ $serviceRequest->city }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($serviceRequest->status === 'assignee') bg-blue-100 text-blue-800
                                        @elseif($serviceRequest->status === 'en_cours') bg-orange-100 text-orange-800
                                        @endif">
                                        {{ $serviceRequest->status === 'assignee' ? 'À démarrer' : 'En cours' }}
                                    </span>
                                    @if($serviceRequest->priority === 'urgente')
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            🔴 Urgent
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun service assigné</p>
                        @endforelse
                    </div>
                </div>

                <!-- Rendez-vous d'aujourd'hui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">📋 Aujourd'hui</h4>
                            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
                        </div>
                        
                        @php
                            $todayAppointments = \App\Models\Appointment::whereHas('serviceRequest', function($query) {
                                $query->where('technicien_id', Auth::id());
                            })
                            ->whereDate('scheduled_at', today())
                            ->where('status', '!=', 'annule')
                            ->with(['client', 'serviceRequest.service'])
                            ->orderBy('scheduled_at')
                            ->get();
                        @endphp

                        @forelse($todayAppointments as $appointment)
                            <div class="border rounded-lg p-3 mb-3 last:mb-0 
                                @if($appointment->scheduled_at < now()) bg-gray-50
                                @elseif($appointment->scheduled_at < now()->addHour()) bg-yellow-50
                                @else bg-white @endif">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 text-sm">{{ $appointment->serviceRequest->service->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->scheduled_at->format('H:i') }} 
                                            ({{ $appointment->duration }}min)
                                        </p>
                                    </div>
                                    <div>
                                        @if($appointment->scheduled_at < now())
                                            <span class="text-xs text-gray-500">Passé</span>
                                        @elseif($appointment->scheduled_at < now()->addHour())
                                            <span class="text-xs text-yellow-600 font-medium">Bientôt</span>
                                        @else
                                            <span class="text-xs text-green-600">À venir</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun rendez-vous aujourd'hui</p>
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-700">
                                    💡 Journée libre ! Profitez-en pour planifier vos prochaines interventions.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Statistiques de performance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">📊 Cette semaine</h4>
                        
                        @php
                            $weekStart = now()->startOfWeek();
                            $weekEnd = now()->endOfWeek();
                            
                            $weekStats = [
                                'services_completed' => Auth::user()->assignedServiceRequests()
                                    ->where('status', 'terminee')
                                    ->whereBetween('completed_at', [$weekStart, $weekEnd])
                                    ->count(),
                                'appointments_this_week' => \App\Models\Appointment::whereHas('serviceRequest', function($query) {
                                    $query->where('technicien_id', Auth::id());
                                })
                                ->whereBetween('scheduled_at', [$weekStart, $weekEnd])
                                ->count(),
                                'hours_worked' => \App\Models\Appointment::whereHas('serviceRequest', function($query) {
                                    $query->where('technicien_id', Auth::id())
                                          ->where('status', 'terminee')
                                          ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()]);
                                })
                                ->where('status', 'termine')
                                ->sum('duration') / 60 ?? 0
                            ];
                        @endphp

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Services terminés</span>
                                <span class="font-medium text-green-600">{{ $weekStats['services_completed'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Rendez-vous</span>
                                <span class="font-medium text-blue-600">{{ $weekStats['appointments_this_week'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Heures estimées</span>
                                <span class="font-medium text-purple-600">{{ number_format($weekStats['hours_worked'], 1) }}h</span>
                            </div>
                        </div>

                        @if($weekStats['services_completed'] > 0)
                            <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                <p class="text-xs text-green-700">
                                    🎉 Excellent travail cette semaine !
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 