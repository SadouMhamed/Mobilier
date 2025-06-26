@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rendez-vous de visite - Aujourd'hui
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('property-appointments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Tous les RDV
            </a>
            <a href="{{ route('property-appointments.pending') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                En attente
            </a>
            <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Retour aux annonces
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

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistiques du jour -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $todayAppointments->where('status', 'confirmed')->count() }}
                            </p>
                            <p class="text-gray-600">Confirmés</p>
                        </div>
                        <div class="text-3xl text-green-600">✅</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $todayAppointments->where('status', 'completed')->count() }}
                            </p>
                            <p class="text-gray-600">Terminés</p>
                        </div>
                        <div class="text-3xl text-blue-600">🏁</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $todayAppointments->where('status', 'pending')->count() }}
                            </p>
                            <p class="text-gray-600">En attente</p>
                        </div>
                        <div class="text-3xl text-yellow-600">⏳</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-gray-600">
                                {{ $todayAppointments->count() }}
                            </p>
                            <p class="text-gray-600">Total</p>
                        </div>
                        <div class="text-3xl text-gray-600">📅</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous par heure -->
        <div class="space-y-6">
            @php
                $appointmentsByHour = $todayAppointments->groupBy(function($appointment) {
                    return $appointment->confirmed_date 
                        ? $appointment->confirmed_date->format('H:00')
                        : $appointment->requested_date->format('H:00');
                })->sortKeys();
            @endphp

            @forelse($appointmentsByHour as $hour => $appointments)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-gray-50 px-6 py-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            🕐 {{ $hour }} ({{ $appointments->count() }} rendez-vous)
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        @foreach($appointments as $appointment)
                            <div class="border rounded-lg p-4 
                                {{ $appointment->status === 'confirmed' ? 'border-green-200 bg-green-50' : '' }}
                                {{ $appointment->status === 'completed' ? 'border-blue-200 bg-blue-50' : '' }}
                                {{ $appointment->status === 'pending' ? 'border-yellow-200 bg-yellow-50' : '' }}
                                {{ $appointment->status === 'cancelled' ? 'border-red-200 bg-red-50' : '' }}">
                                
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-medium text-gray-900">
                                                {{ $appointment->property->title }}
                                            </h4>
                                            
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $appointment->status_label }}
                                            </span>

                                            @php
                                                $appointmentTime = $appointment->confirmed_date ?: $appointment->requested_date;
                                                $isPast = $appointmentTime->isPast();
                                                $isNow = $appointmentTime->between(now()->subMinutes(15), now()->addMinutes(15));
                                            @endphp

                                            @if($isNow && $appointment->status === 'confirmed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 animate-pulse">
                                                    🔔 En cours
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-3">
                                            <div>
                                                <strong>Client:</strong> {{ $appointment->client_name }}
                                                <br>
                                                <span class="text-xs">{{ $appointment->client_email }}</span>
                                                @if($appointment->client_phone)
                                                    <br>
                                                    <span class="text-xs">📞 {{ $appointment->client_phone }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>Heure exacte:</strong> 
                                                <br>
                                                <span class="font-medium">
                                                    {{ ($appointment->confirmed_date ?: $appointment->requested_date)->format('H:i') }}
                                                </span>
                                                @if($appointment->confirmed_date && $appointment->confirmed_date != $appointment->requested_date)
                                                    <br>
                                                    <span class="text-xs text-blue-600">(modifiée par admin)</span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>Propriété:</strong> 
                                                <br>
                                                <span class="text-xs">{{ $appointment->property->address }}</span>
                                                <br>
                                                <span class="text-xs">{{ $appointment->property->city }}</span>
                                                <br>
                                                <span class="text-xs font-medium text-blue-600">
                                                    {{ number_format($appointment->property->price, 0, ',', ' ') }} DZD
                                                </span>
                                            </div>
                                        </div>

                                        @if($appointment->admin_notes)
                                        <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-300 mb-2">
                                            <h5 class="font-medium text-blue-800 text-sm mb-1">📝 Notes administratives</h5>
                                            <p class="text-blue-700 text-sm">{{ $appointment->admin_notes }}</p>
                                        </div>
                                        @endif

                                        @if($appointment->message)
                                        <div class="bg-gray-50 p-3 rounded border-l-4 border-gray-300 mb-2">
                                            <h5 class="font-medium text-gray-800 text-sm mb-1">💬 Message du client</h5>
                                            <p class="text-gray-700 text-sm">{{ $appointment->message }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="text-right ml-4">
                                        <div class="text-xs text-gray-500 mb-2">
                                            #{{ $appointment->id }}
                                        </div>
                                        
                                        @if($isNow && $appointment->status === 'confirmed')
                                            <div class="text-xs font-bold text-orange-600 animate-pulse">
                                                🔔 MAINTENANT
                                            </div>
                                        @elseif($isPast && $appointment->status === 'confirmed')
                                            <div class="text-xs text-red-600">
                                                ⚠️ En retard
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-3">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('property-appointments.show', $appointment) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Voir détails
                                        </a>
                                        <a href="{{ route('properties.show', $appointment->property) }}" 
                                           class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                            Voir l'annonce
                                        </a>
                                        
                                        @if($appointment->status === 'pending')
                                            <button onclick="openConfirmModal({{ $appointment->id }})" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                ✅ Confirmer
                                            </button>
                                        @endif

                                        @if($appointment->status === 'confirmed' && !$appointment->confirmed_date->addHours(2)->isPast())
                                            <button onclick="openCompleteModal({{ $appointment->id }})" 
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                🏁 Terminer
                                            </button>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        Créé {{ $appointment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous aujourd'hui</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Aucune visite prévue pour aujourd'hui.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('property-appointments.pending') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                                Voir les demandes en attente
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal de confirmation (réutilisé) -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Confirmer le rendez-vous</h3>
            <form id="confirmForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="confirmed_date" class="block text-sm font-medium text-gray-700">Date et heure confirmées *</label>
                    <input type="datetime-local" name="confirmed_date" id="confirmed_date" 
                           min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Instructions, informations complémentaires..."></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeConfirmModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de finalisation -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Finaliser le rendez-vous</h3>
            <form id="completeForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700">Notes de finalisation</label>
                    <textarea name="completion_notes" id="completion_notes" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Comment s'est passée la visite ? Le client était-il intéressé ?"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeCompleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md">
                        Terminer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openConfirmModal(appointmentId) {
    document.getElementById('confirmForm').action = `/property-appointments/${appointmentId}/confirm`;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function openCompleteModal(appointmentId) {
    document.getElementById('completeForm').action = `/property-appointments/${appointmentId}/complete`;
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

// Auto-refresh toutes les 5 minutes pour les rendez-vous en cours
setInterval(function() {
    if (document.querySelectorAll('.animate-pulse').length > 0) {
        window.location.reload();
    }
}, 300000); // 5 minutes
</script>
@endsection 