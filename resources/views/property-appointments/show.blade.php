@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Détails du rendez-vous #{{ $propertyAppointment->id }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('property-appointments.index') }}" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Retour à la liste
            </a>
            @if($propertyAppointment->status === 'pending')
                <a href="{{ route('property-appointments.pending') }}" class="px-4 py-2 text-white bg-yellow-600 rounded hover:bg-yellow-700">
                    En attente
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Messages -->
        @if (session('success'))
            <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 rounded border border-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 rounded border border-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Informations principales -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Statut et actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 
                    {{ $propertyAppointment->status === 'pending' ? 'border-yellow-500' : '' }}
                    {{ $propertyAppointment->status === 'confirmed' ? 'border-green-500' : '' }}
                    {{ $propertyAppointment->status === 'completed' ? 'border-blue-500' : '' }}
                    {{ $propertyAppointment->status === 'cancelled' ? 'border-red-500' : '' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-gray-900">
                                    Statut du rendez-vous
                                </h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $propertyAppointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $propertyAppointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $propertyAppointment->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $propertyAppointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $propertyAppointment->status_label }}
                                </span>
                            </div>
                            
                            <div class="text-right">
                                <div class="mb-1 text-sm text-gray-500">
                                    Rendez-vous #{{ $propertyAppointment->id }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Créé {{ $propertyAppointment->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        @if(auth()->user()->role === 'admin')
                            <div class="flex pt-4 space-x-2 border-t">
                                @if($propertyAppointment->status === 'pending')
                                    <button onclick="openConfirmModal()" 
                                            class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                                        ✅ Confirmer
                                    </button>
                                    <button onclick="openCancelModal()" 
                                            class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                        ❌ Refuser
                                    </button>
                                @elseif($propertyAppointment->status === 'confirmed')
                                    <button onclick="openCompleteModal()" 
                                            class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        🏁 Terminer
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations du rendez-vous -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">
                            📅 Informations du rendez-vous
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h4 class="mb-2 font-medium text-gray-800">Date et heure demandées</h4>
                                <p class="text-gray-600">
                                    {{ $propertyAppointment->requested_date->format('d/m/Y à H:i') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $propertyAppointment->requested_date->diffForHumans() }}
                                </p>
                            </div>

                            @if($propertyAppointment->confirmed_date)
                            <div>
                                <h4 class="mb-2 font-medium text-gray-800">Date et heure confirmées</h4>
                                <p class="text-gray-600">
                                    {{ $propertyAppointment->confirmed_date->format('d/m/Y à H:i') }}
                                </p>
                                @if($propertyAppointment->confirmed_date != $propertyAppointment->requested_date)
                                    <p class="text-xs text-blue-600">
                                        ⚠️ Modifiée par l'administrateur
                                    </p>
                                @endif
                            </div>
                            @endif
                        </div>

                        @if($propertyAppointment->message)
                        <div class="mt-6">
                            <h4 class="mb-2 font-medium text-gray-800">💬 Message du client</h4>
                            <div class="p-4 bg-gray-50 rounded border-l-4 border-gray-300">
                                <p class="text-gray-700">{{ $propertyAppointment->message }}</p>
                            </div>
                        </div>
                        @endif

                        @if($propertyAppointment->admin_notes)
                        <div class="mt-6">
                            <h4 class="mb-2 font-medium text-gray-800">📝 Notes administratives</h4>
                            <div class="p-4 bg-blue-50 rounded border-l-4 border-blue-300">
                                <p class="text-blue-700">{{ $propertyAppointment->admin_notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($propertyAppointment->completion_notes)
                        <div class="mt-6">
                            <h4 class="mb-2 font-medium text-gray-800">🏁 Notes de finalisation</h4>
                            <div class="p-4 bg-green-50 rounded border-l-4 border-green-300">
                                <p class="text-green-700">{{ $propertyAppointment->completion_notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($propertyAppointment->cancellation_reason)
                        <div class="mt-6">
                            <h4 class="mb-2 font-medium text-gray-800">❌ Motif de refus</h4>
                            <div class="p-4 bg-red-50 rounded border-l-4 border-red-300">
                                <p class="text-red-700">{{ $propertyAppointment->cancellation_reason }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informations du client -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">
                            👤 Informations du client
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h4 class="mb-2 font-medium text-gray-800">Nom complet</h4>
                                <p class="text-gray-600">{{ $propertyAppointment->client_name }}</p>
                            </div>
                            
                            <div>
                                <h4 class="mb-2 font-medium text-gray-800">Email</h4>
                                <p class="text-gray-600">
                                    <a href="mailto:{{ $propertyAppointment->client_email }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $propertyAppointment->client_email }}
                                    </a>
                                </p>
                            </div>
                            
                            @if($propertyAppointment->client_phone)
                            <div>
                                <h4 class="mb-2 font-medium text-gray-800">Téléphone</h4>
                                <p class="text-gray-600">
                                    <a href="tel:{{ $propertyAppointment->client_phone }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $propertyAppointment->client_phone }}
                                    </a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Informations de la propriété -->
            <div class="space-y-6">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">
                            🏠 Propriété concernée
                        </h3>
                        
                        <div class="mb-4">
                            <h4 class="mb-2 font-medium text-gray-800">{{ $propertyAppointment->property->title }}</h4>
                            <p class="mb-2 text-2xl font-bold text-blue-600">
                                {{ number_format($propertyAppointment->property->price, 0, ',', ' ') }} DZD
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $propertyAppointment->property->property_type }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <h5 class="text-sm font-medium text-gray-800">Adresse</h5>
                                <p class="text-sm text-gray-600">
                                    {{ $propertyAppointment->property->address }}<br>
                                    {{ $propertyAppointment->property->postal_code }} {{ $propertyAppointment->property->city }}
                                </p>
                            </div>

                            @if($propertyAppointment->property->area)
                            <div>
                                <h5 class="text-sm font-medium text-gray-800">Surface</h5>
                                <p class="text-sm text-gray-600">{{ $propertyAppointment->property->area }} m²</p>
                            </div>
                            @endif

                            @if($propertyAppointment->property->rooms)
                            <div>
                                <h5 class="text-sm font-medium text-gray-800">Nombre de pièces</h5>
                                <p class="text-sm text-gray-600">{{ $propertyAppointment->property->rooms }} pièces</p>
                            </div>
                            @endif
                        </div>

                        <div class="pt-4 mt-6 border-t">
                            <a href="{{ route('properties.show', $propertyAppointment->property) }}" 
                               class="inline-flex justify-center items-center px-4 py-2 w-full text-xs font-semibold tracking-widest text-white uppercase bg-purple-600 rounded-md border border-transparent hover:bg-purple-700">
                                Voir l'annonce complète
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">
                            ⏱️ Chronologie
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="mt-2 w-2 h-2 bg-gray-400 rounded-full"></div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <strong>Demande créée</strong><br>
                                        {{ $propertyAppointment->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>

                            @if($propertyAppointment->confirmed_date)
                            <div class="flex items-start space-x-3">
                                <div class="mt-2 w-2 h-2 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <strong>Rendez-vous confirmé</strong><br>
                                        {{ $propertyAppointment->updated_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($propertyAppointment->status === 'completed')
                            <div class="flex items-start space-x-3">
                                <div class="mt-2 w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <strong>Rendez-vous terminé</strong><br>
                                        {{ $propertyAppointment->updated_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($propertyAppointment->status === 'cancelled')
                            <div class="flex items-start space-x-3">
                                <div class="mt-2 w-2 h-2 bg-red-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <strong>Rendez-vous refusé</strong><br>
                                        {{ $propertyAppointment->updated_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Modal de confirmation -->
<div id="confirmModal" class="hidden overflow-y-auto fixed inset-0 w-full h-full bg-gray-600 bg-opacity-50">
    <div class="relative top-20 p-5 mx-auto w-96 bg-white rounded-md border shadow-lg">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-center text-gray-900">Confirmer le rendez-vous</h3>
            <form action="{{ route('property-appointments.confirm', $propertyAppointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="confirmed_date" class="block text-sm font-medium text-gray-700">Date et heure confirmées *</label>
                    <input type="datetime-local" name="confirmed_date" id="confirmed_date" 
                           value="{{ $propertyAppointment->requested_date->format('Y-m-d\TH:i') }}"
                           min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                           class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                              class="block mt-1 w-full rounded-md border-gray-300 shadow-sm"
                              placeholder="Instructions, informations complémentaires...">{{ $propertyAppointment->admin_notes }}</textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeConfirmModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-white bg-green-600 rounded-md">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div id="cancelModal" class="hidden overflow-y-auto fixed inset-0 w-full h-full bg-gray-600 bg-opacity-50">
    <div class="relative top-20 p-5 mx-auto w-96 bg-white rounded-md border shadow-lg">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-center text-gray-900">Refuser le rendez-vous</h3>
            <form action="{{ route('property-appointments.cancel', $propertyAppointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Motif de refus *</label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                              class="block mt-1 w-full rounded-md border-gray-300 shadow-sm"
                              placeholder="Expliquez pourquoi ce rendez-vous est refusé..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md">
                        Retour
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-white bg-red-600 rounded-md">
                        Refuser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de finalisation -->
<div id="completeModal" class="hidden overflow-y-auto fixed inset-0 w-full h-full bg-gray-600 bg-opacity-50">
    <div class="relative top-20 p-5 mx-auto w-96 bg-white rounded-md border shadow-lg">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-center text-gray-900">Finaliser le rendez-vous</h3>
            <form action="{{ route('property-appointments.complete', $propertyAppointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700">Notes de finalisation</label>
                    <textarea name="completion_notes" id="completion_notes" rows="4" 
                              class="block mt-1 w-full rounded-md border-gray-300 shadow-sm"
                              placeholder="Comment s'est passée la visite ? Le client était-il intéressé ?"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeCompleteModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-white bg-blue-600 rounded-md">
                        Terminer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openConfirmModal() {
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function openCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}
</script>
@endif

@endsection 