@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rendez-vous de visite en attente
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('property-appointments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Tous les RDV
            </a>
            <a href="{{ route('property-appointments.today') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Aujourd'hui
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

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $pendingAppointments->total() }}
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
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $pendingAppointments->where('requested_date', '>', now()->addDay())->count() }}
                            </p>
                            <p class="text-gray-600">Pour demain+</p>
                        </div>
                        <div class="text-3xl text-blue-600">📅</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-red-600">
                                {{ $pendingAppointments->where('requested_date', '<', now()->addDay())->count() }}
                            </p>
                            <p class="text-gray-600">Urgents</p>
                        </div>
                        <div class="text-3xl text-red-600">🚨</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des rendez-vous en attente -->
        <div class="space-y-4">
            @forelse($pendingAppointments as $appointment)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $appointment->property->title }}
                                    </h3>
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>

                                    @if($appointment->requested_date < now()->addDay())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Urgent
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                    <div>
                                        <strong>Client:</strong> {{ $appointment->client_name }}
                                        <br>
                                        <span class="text-xs">{{ $appointment->client_email }}</span>
                                        @if($appointment->client_phone)
                                            <br>
                                            <span class="text-xs">{{ $appointment->client_phone }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>Date demandée:</strong> 
                                        <br>
                                        <span class="font-medium">{{ $appointment->requested_date->format('d/m/Y à H:i') }}</span>
                                        <br>
                                        <span class="text-xs {{ $appointment->requested_date < now()->addDay() ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $appointment->requested_date->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>Propriété:</strong> 
                                        <br>
                                        <span class="text-xs">{{ $appointment->property->address }}</span>
                                        <br>
                                        <span class="text-xs">{{ $appointment->property->city }}</span>
                                    </div>
                                </div>

                                @if($appointment->message)
                                <div class="bg-gray-50 p-3 rounded border-l-4 border-gray-300 mb-3">
                                    <h4 class="font-medium text-gray-800 text-sm mb-1">💬 Message du client</h4>
                                    <p class="text-gray-700 text-sm">{{ $appointment->message }}</p>
                                </div>
                                @endif
                            </div>

                            <div class="text-right ml-4">
                                                                    <div class="text-lg font-bold text-blue-600">
                                        {{ number_format($appointment->property->price, 0, ',', ' ') }} DZD
                                    </div>
                                <div class="text-sm text-gray-500">
                                    {{ $appointment->property->property_type }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Demandé {{ $appointment->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex space-x-2">
                                <a href="{{ route('property-appointments.show', $appointment) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>
                                <a href="{{ route('properties.show', $appointment->property) }}" 
                                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    Voir l'annonce
                                </a>
                                
                                <button onclick="openConfirmModal({{ $appointment->id }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium">
                                    ✅ Confirmer
                                </button>
                                
                                <button onclick="openCancelModal({{ $appointment->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium">
                                    ❌ Refuser
                                </button>
                            </div>

                            <div class="text-sm text-gray-500">
                                #{{ $appointment->id }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous en attente</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Toutes les demandes de visite ont été traitées.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('property-appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Voir tous les rendez-vous
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pendingAppointments->hasPages())
            <div class="mt-8">
                {{ $pendingAppointments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation -->
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
                           min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
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

<!-- Modal de refus -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Refuser le rendez-vous</h3>
            <form id="cancelForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Motif de refus *</label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Expliquez pourquoi ce rendez-vous est refusé..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Retour
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md">
                        Refuser
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
    
    // Pré-remplir avec la date demandée
    const appointment = document.querySelector(`[data-appointment-id="${appointmentId}"]`);
    if (appointment) {
        const requestedDate = appointment.dataset.requestedDate;
        if (requestedDate) {
            document.getElementById('confirmed_date').value = requestedDate;
        }
    }
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function openCancelModal(appointmentId) {
    document.getElementById('cancelForm').action = `/property-appointments/${appointmentId}/cancel`;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}
</script>
@endsection 