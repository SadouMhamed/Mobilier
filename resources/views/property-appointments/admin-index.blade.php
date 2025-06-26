<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des rendez-vous d\'annonces') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistiques -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Statistiques des rendez-vous</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-800">{{ $stats['pending'] }}</div>
                            <div class="text-sm text-yellow-600">En attente</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-2xl font-bold text-blue-800">{{ $stats['confirmed'] }}</div>
                            <div class="text-sm text-blue-600">Confirmés</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-2xl font-bold text-green-800">{{ $stats['completed'] }}</div>
                            <div class="text-sm text-green-600">Terminés</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="text-2xl font-bold text-red-800">{{ $stats['cancelled'] }}</div>
                            <div class="text-sm text-red-600">Annulés</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <div class="text-2xl font-bold text-purple-800">{{ $stats['today'] }}</div>
                            <div class="text-sm text-purple-600">Aujourd'hui</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('property-appointments.pending') }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            RDV en attente ({{ $stats['pending'] }})
                        </a>
                        
                        <a href="{{ route('property-appointments.today') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 8v-2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h6"/>
                            </svg>
                            RDV d'aujourd'hui ({{ $stats['today'] }})
                        </a>

                        <a href="{{ route('property-appointments.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                            </svg>
                            Tous les RDV
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Rendez-vous en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">RDV en attente</h3>
                            <a href="{{ route('property-appointments.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Voir tous →
                            </a>
                        </div>
                        
                        @if($pendingAppointments->count() > 0)
                            <div class="space-y-3">
                                @foreach($pendingAppointments as $appointment)
                                    <div class="border rounded-lg p-3 bg-yellow-50">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-sm">
                                                    <a href="{{ route('properties.show', $appointment->property) }}" class="text-blue-600 hover:underline">
                                                        {{ Str::limit($appointment->property->title, 30) }}
                                                    </a>
                                                </h4>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Par: {{ $appointment->client_name }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Date: {{ $appointment->requested_date->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                            <a href="{{ route('property-appointments.show', $appointment) }}" 
                                               class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                                Traiter
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Aucun rendez-vous en attente</p>
                        @endif
                    </div>
                </div>

                <!-- Rendez-vous d'aujourd'hui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">RDV d'aujourd'hui</h3>
                            <a href="{{ route('property-appointments.today') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Voir tous →
                            </a>
                        </div>
                        
                        @if($todayAppointments->count() > 0)
                            <div class="space-y-3">
                                @foreach($todayAppointments as $appointment)
                                    <div class="border rounded-lg p-3 bg-blue-50">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-sm">
                                                    <a href="{{ route('properties.show', $appointment->property) }}" class="text-blue-600 hover:underline">
                                                        {{ Str::limit($appointment->property->title, 30) }}
                                                    </a>
                                                </h4>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Client: {{ $appointment->client_name }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Heure: {{ $appointment->confirmed_date->format('H:i') }}
                                                </p>
                                            </div>
                                            <a href="{{ route('property-appointments.show', $appointment) }}" 
                                               class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Aucun rendez-vous aujourd'hui</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rendez-vous récents -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Rendez-vous récents</h3>
                        <a href="{{ route('property-appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Voir tous →
                        </a>
                    </div>
                    
                    @if($recentAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Annonce</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandeur</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date souhaitée</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentAppointments as $appointment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('properties.show', $appointment->property) }}" class="text-blue-600 hover:underline">
                                                        {{ Str::limit($appointment->property->title, 40) }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $appointment->property->city }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $appointment->client_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->client_email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $appointment->requested_date->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                                                    {{ $appointment->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('property-appointments.show', $appointment) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucun rendez-vous récent</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 