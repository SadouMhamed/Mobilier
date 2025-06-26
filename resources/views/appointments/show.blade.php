@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Détails du Rendez-vous</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Rendez-vous #{{ $appointment->id }} - {{ $appointment->scheduled_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('appointments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @php
                $statusClasses = [
                    'planifie' => 'bg-yellow-100 text-yellow-800',
                    'confirme' => 'bg-blue-100 text-blue-800',
                    'termine' => 'bg-green-100 text-green-800',
                    'annule' => 'bg-red-100 text-red-800',
                ];
                $statusLabels = [
                    'planifie' => 'Planifié',
                    'confirme' => 'Confirmé',
                    'termine' => 'Terminé',
                    'annule' => 'Annulé',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
            </span>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Appointment Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informations du Rendez-vous</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date et heure</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $appointment->scheduled_at->format('l d F Y à H:i') }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durée prévue</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $appointment->duration }} minutes
                                </dd>
                            </div>

                            @if($appointment->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de completion</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $appointment->completed_at->format('d/m/Y à H:i') }}
                                </dd>
                            </div>
                            @endif

                            @if($appointment->cancellation_reason)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Raison d'annulation</dt>
                                <dd class="mt-1 text-sm text-red-600">
                                    {{ $appointment->cancellation_reason }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Service Request Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Demande de Service Associée</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Service</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointment->serviceRequest->description }}</p>
                            </div>
                            
                            @if($appointment->serviceRequest->technicien)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Technicien assigné</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointment->serviceRequest->technicien->name }}</p>
                                <p class="text-sm text-gray-500">{{ $appointment->serviceRequest->technicien->email }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($appointment->notes)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Notes</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Client Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Client</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $appointment->client->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $appointment->client->email }}</p>
                                @if($appointment->client->phone)
                                <p class="text-sm text-gray-500">{{ $appointment->client->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Bouton PDF -->
                        <a href="{{ route('appointments.pdf', $appointment) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger PDF
                        </a>

                        @if((auth()->user()->role === 'technicien' && $appointment->serviceRequest->technicien_id === auth()->id()) || auth()->user()->role === 'admin')
                            <!-- Proposition de nouveau RDV pour technicien -->
                            @if(auth()->user()->role === 'technicien' && $appointment->status !== 'termine' && !$appointment->proposed_date)
                            <button type="button" 
                                    onclick="document.getElementById('propose-form').style.display='block'"
                                    class="w-full px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mb-2">
                                Proposer nouveau RDV
                            </button>
                            @endif

                            @if($appointment->status === 'planifie')
                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Confirmer
                                </button>
                            </form>
                            @endif
                            
                            @if(in_array($appointment->status, ['planifie', 'confirme']))
                            <button type="button" 
                                    onclick="document.getElementById('complete-form').style.display='block'"
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Marquer comme terminé
                            </button>
                            @endif
                        @endif

                        <!-- Propositions en attente (pour admin) -->
                        @if(auth()->user()->role === 'admin' && $appointment->proposed_date)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                            <h4 class="font-medium text-yellow-800 text-sm">Proposition de RDV</h4>
                            <p class="text-yellow-700 text-sm">{{ $appointment->proposed_date->format('d/m/Y à H:i') }}</p>
                            <p class="text-yellow-600 text-xs">{{ $appointment->proposed_reason }}</p>
                            <div class="mt-2 space-x-2">
                                <form action="{{ route('appointments.approve-proposal', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-2 py-1 rounded">
                                        Approuver
                                    </button>
                                </form>
                                <button onclick="document.getElementById('reject-proposal-form').style.display='block'" class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded">
                                    Rejeter
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Form Modal -->
<div id="complete-form" style="display:none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Marquer comme terminé</h3>
            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes du rendez-vous (optionnel)</label>
                    <textarea name="notes" id="notes" rows="2" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Notes sur le rendez-vous..."></textarea>
                </div>
                <div class="mb-4">
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700">Notes de travail effectué</label>
                    <textarea name="completion_notes" id="completion_notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Décrivez les travaux effectués..." required></textarea>
                </div>
                <div class="mb-4">
                    <label for="final_notes" class="block text-sm font-medium text-gray-700">Rapport final (optionnel)</label>
                    <textarea name="final_notes" id="final_notes" rows="2" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Recommandations, observations finales..."></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" 
                            onclick="document.getElementById('complete-form').style.display='none'"
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

<!-- Modal de proposition de nouveau RDV -->
<div id="propose-form" style="display:none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Proposer un nouveau rendez-vous</h3>
            <form action="{{ route('appointments.propose', $appointment) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="proposed_date" class="block text-sm font-medium text-gray-700">Nouvelle date et heure</label>
                    <input type="datetime-local" name="proposed_date" id="proposed_date" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="proposed_reason" class="block text-sm font-medium text-gray-700">Raison du changement</label>
                    <textarea name="proposed_reason" id="proposed_reason" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Expliquez pourquoi vous proposez ce nouveau créneau..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" 
                            onclick="document.getElementById('propose-form').style.display='none'"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md">
                        Proposer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de rejet de proposition -->
<div id="reject-proposal-form" style="display:none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center">Rejeter la proposition</h3>
            <form action="{{ route('appointments.reject-proposal', $appointment) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Raison du rejet</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Expliquez pourquoi vous rejetez cette proposition..." required></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" 
                            onclick="document.getElementById('reject-proposal-form').style.display='none'"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md">
                        Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 