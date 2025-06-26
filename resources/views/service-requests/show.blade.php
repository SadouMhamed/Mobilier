<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la demande de service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- En-tête avec statut -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $serviceRequest->service->name }}</h3>
                            <p class="text-gray-600">Demande #{{ $serviceRequest->id }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium
                            @if($serviceRequest->status == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($serviceRequest->status == 'assignee') bg-blue-100 text-blue-800
                            @elseif($serviceRequest->status == 'en_cours') bg-orange-100 text-orange-800
                            @elseif($serviceRequest->status == 'terminee') bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                        </span>
                    </div>

                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900">Informations générales</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->client->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service demandé</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->service->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priorité</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($serviceRequest->priority == 'urgente') bg-red-100 text-red-800
                                        @elseif($serviceRequest->priority == 'haute') bg-orange-100 text-orange-800
                                        @elseif($serviceRequest->priority == 'normale') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($serviceRequest->priority) }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date préférée</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($serviceRequest->preferred_date)
                                        {{ $serviceRequest->preferred_date->format('d/m/Y à H:i') }}
                                    @else
                                        Non spécifiée
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900">Coordonnées</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Adresse</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->address }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ville</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->city }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Code postal</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->postal_code }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Description</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $serviceRequest->description }}</p>
                        </div>
                    </div>

                    <!-- Technicien assigné -->
                    @if($serviceRequest->technicien)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Technicien assigné</h4>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900 font-medium">{{ $serviceRequest->technicien->name }}</p>
                            <p class="text-sm text-gray-600">{{ $serviceRequest->technicien->email }}</p>
                            @if($serviceRequest->assigned_at)
                                <p class="text-xs text-gray-500 mt-1">Assigné le {{ $serviceRequest->assigned_at->format('d/m/Y à H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Dates importantes -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Historique</h4>
                        <div class="space-y-2">
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Créée le :</span>
                                <span class="text-gray-900">{{ $serviceRequest->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            
                            @if($serviceRequest->started_at)
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Commencée le :</span>
                                <span class="text-gray-900">{{ $serviceRequest->started_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                            
                            @if($serviceRequest->completed_at)
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Terminée le :</span>
                                <span class="text-gray-900">{{ $serviceRequest->completed_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes diverses -->
                    <div class="mb-6 space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Notes et commentaires</h4>
                        
                        <!-- Notes admin -->
                        @if($serviceRequest->admin_notes || Auth::user()->role === 'admin')
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h5 class="font-medium text-blue-800 mb-2">Notes administrateur</h5>
                            @if($serviceRequest->admin_notes)
                                <p class="text-sm text-blue-700 mb-3">{{ $serviceRequest->admin_notes }}</p>
                            @endif
                            
                            @if(Auth::user()->role === 'admin' && !$serviceRequest->is_archived)
                                <form action="{{ route('service-requests.admin-note', $serviceRequest) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <textarea name="admin_notes" rows="2" 
                                              class="w-full border-blue-300 rounded-md shadow-sm text-sm"
                                              placeholder="Ajouter une note administrative...">{{ $serviceRequest->admin_notes }}</textarea>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">
                                        {{ $serviceRequest->admin_notes ? 'Modifier' : 'Ajouter' }} note
                                    </button>
                                </form>
                            @elseif(!$serviceRequest->admin_notes)
                                <p class="text-sm text-blue-600 italic">Aucune note administrative</p>
                            @endif
                        </div>
                        @endif

                        <!-- Notes technicien -->
                        @if($serviceRequest->technicien_notes)
                        <div class="bg-green-50 rounded-lg p-4">
                            <h5 class="font-medium text-green-800 mb-2">Notes du technicien</h5>
                            <p class="text-sm text-green-700">{{ $serviceRequest->technicien_notes }}</p>
                        </div>
                        @endif

                        <!-- Rapport final -->
                        @if($serviceRequest->final_notes)
                        <div class="bg-purple-50 rounded-lg p-4">
                            <h5 class="font-medium text-purple-800 mb-2">Rapport final</h5>
                            <p class="text-sm text-purple-700">{{ $serviceRequest->final_notes }}</p>
                        </div>
                        @endif

                        <!-- Notes de completion (legacy) -->
                        @if($serviceRequest->completion_notes && !$serviceRequest->final_notes)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="font-medium text-gray-800 mb-2">Notes de fin</h5>
                            <p class="text-sm text-gray-700">{{ $serviceRequest->completion_notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Rapports de tâches et facturation -->
                    @if($serviceRequest->status === 'terminee' || $serviceRequest->taskReports->count() > 0 || $serviceRequest->invoice)
                    <div class="mb-6 space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Rapports et facturation</h4>
                        
                        <!-- Rapports de tâches -->
                        @if($serviceRequest->taskReports->count() > 0)
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <h5 class="font-medium text-indigo-800 mb-3">Rapports de tâches ({{ $serviceRequest->taskReports->count() }})</h5>
                                <div class="space-y-2">
                                    @foreach($serviceRequest->taskReports as $report)
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <span class="text-sm font-medium text-indigo-700">{{ $report->task_title }}</span>
                                                <span class="text-xs text-indigo-600 ml-2">({{ $report->created_at->format('d/m/Y') }})</span>
                                            </div>
                                            <a href="{{ route('task-reports.show', $report) }}" 
                                                class="text-indigo-600 hover:text-indigo-800 text-sm">Voir détail →</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($serviceRequest->status === 'terminee' && Auth::user()->role === 'technicien' && Auth::user()->id === $serviceRequest->technicien_id)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-yellow-800">Rapport de tâche requis</h5>
                                        <p class="text-sm text-yellow-700">Veuillez soumettre un rapport pour cette intervention terminée.</p>
                                    </div>
                                    <a href="{{ route('task-reports.create', $serviceRequest) }}" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Ajouter rapport
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Facturation -->
                        @if($serviceRequest->invoice)
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-green-800">Facture #{{ $serviceRequest->invoice->invoice_number }}</h5>
                                        <p class="text-sm text-green-700">
                                            Montant : {{ number_format($serviceRequest->invoice->total_amount, 0, ',', ' ') }} DZD | 
                                            Status : {{ ucfirst($serviceRequest->invoice->status) }}
                                        </p>
                                    </div>
                                    <div class="space-x-2">
                                        <a href="{{ route('invoices.show', $serviceRequest->invoice) }}" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Voir facture
                                        </a>
                                        <a href="{{ route('invoices.pdf', $serviceRequest->invoice) }}" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif($serviceRequest->taskReports->count() > 0 && Auth::user()->role === 'admin')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-blue-800">Facturation en attente</h5>
                                        <p class="text-sm text-blue-700">Des rapports de tâches sont disponibles pour créer une facture.</p>
                                    </div>
                                    <a href="{{ route('invoices.create', $serviceRequest) }}" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Créer facture
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('service-requests.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Retour à la liste
                        </a>

                        <div class="space-x-2">
                            <!-- Bouton PDF -->
                            <a href="{{ route('service-requests.pdf', $serviceRequest) }}" 
                               target="_blank"
                               class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Imprimer PDF
                            </a>

                            @if(Auth::user() && Auth::user()->role === 'client' && Auth::user()->id === $serviceRequest->client_id && in_array($serviceRequest->status, ['en_attente', 'assignee']))
                                <a href="{{ route('service-requests.edit', $serviceRequest) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Modifier
                                </a>
                            @endif

                            @if($serviceRequest->status === 'assignee')
                                <a href="{{ route('appointments.create', ['service_request_id' => $serviceRequest->id]) }}" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Planifier RDV
                                </a>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 