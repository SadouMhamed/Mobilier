<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(Auth::user()->role === 'admin')
                    {{ __('Gestion des Services') }}
                @elseif(Auth::user()->role === 'technicien')
                    {{ __('Mes Interventions') }}
                @else
                    {{ __('Mes Demandes de Service') }}
                @endif
            </h2>
            @if(Auth::user()->role === 'client')
                <a href="{{ route('service-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Demander un service
                </a>
            @endif
        </div>
    </x-slot>

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

            <!-- Filtres pour admin -->
            @if(Auth::user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex flex-wrap space-x-2 space-y-2 items-center">
                            <a href="{{ route('service-requests.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Toutes ({{ $serviceRequests->total() }})
                            </a>
                            <a href="{{ route('service-requests.index', ['status' => 'en_attente']) }}" class="px-4 py-2 bg-yellow-200 rounded-lg hover:bg-yellow-300">
                                En attente
                            </a>
                            <a href="{{ route('service-requests.index', ['status' => 'assignee']) }}" class="px-4 py-2 bg-blue-200 rounded-lg hover:bg-blue-300">
                                Assignées
                            </a>
                            <a href="{{ route('service-requests.index', ['status' => 'en_cours']) }}" class="px-4 py-2 bg-orange-200 rounded-lg hover:bg-orange-300">
                                En cours
                            </a>
                            <a href="{{ route('service-requests.index', ['status' => 'terminee']) }}" class="px-4 py-2 bg-green-200 rounded-lg hover:bg-green-300">
                                Terminées
                            </a>
                            <a href="{{ route('service-requests.archived') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                📁 Archivées
                            </a>
                            <a href="{{ route('service-requests.evaluations') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                📊 Évaluations
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Liste des demandes -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($serviceRequests as $serviceRequest)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $serviceRequest->service->name }}
                                        </h3>
                                        
                                        <!-- Badge statut -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($serviceRequest->status == 'en_attente') bg-yellow-100 text-yellow-800
                                            @elseif($serviceRequest->status == 'assignee') bg-blue-100 text-blue-800
                                            @elseif($serviceRequest->status == 'en_cours') bg-orange-100 text-orange-800
                                            @elseif($serviceRequest->status == 'terminee') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                                        </span>

                                        <!-- Badge priorité -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($serviceRequest->priority == 'urgente') bg-red-100 text-red-800
                                            @elseif($serviceRequest->priority == 'haute') bg-orange-100 text-orange-800
                                            @elseif($serviceRequest->priority == 'normale') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($serviceRequest->priority == 'urgente') 🔴
                                            @elseif($serviceRequest->priority == 'haute') 🟠
                                            @elseif($serviceRequest->priority == 'normale') 🟡
                                            @else 🟢 @endif
                                            {{ ucfirst($serviceRequest->priority) }}
                                        </span>
                                    </div>

                                    <!-- Client (pour admin/technicien) -->
                                    @if(Auth::user()->role !== 'client')
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Client:</strong> {{ $serviceRequest->client->name }} ({{ $serviceRequest->client->email }})
                                        </p>
                                    @endif

                                    <!-- Technicien (pour admin/client) -->
                                    @if(Auth::user()->role !== 'technicien' && $serviceRequest->technicien)
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Technicien:</strong> {{ $serviceRequest->technicien->name }}
                                        </p>
                                    @endif

                                    <p class="text-gray-700 mb-2">{{ Str::limit($serviceRequest->description, 150) }}</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500">
                                        <div>
                                            <strong>Adresse:</strong> {{ $serviceRequest->address }}, {{ $serviceRequest->city }}
                                        </div>
                                        <div>
                                            <strong>Prix:</strong> {{ number_format($serviceRequest->service->price, 0, ',', ' ') }} DZD
                                        </div>
                                        <div>
                                            <strong>Créée le:</strong> {{ $serviceRequest->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>

                                    @if($serviceRequest->preferred_date)
                                        <div class="text-sm text-gray-500 mt-2">
                                            <strong>Date préférée:</strong> {{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('d/m/Y à H:i') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="text-2xl font-bold text-blue-600 ml-4">
                                    {{ number_format($serviceRequest->service->price, 0, ',', ' ') }} DZD
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-4 border-t">
                                <div class="flex space-x-2">
                                    <a href="{{ route('service-requests.show', $serviceRequest) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails
                                    </a>
                                </div>

                                <div class="flex space-x-2">
                                    <!-- Actions Admin -->
                                    @if(Auth::user()->role === 'admin')
                                        @if($serviceRequest->status === 'en_attente')
                                            <button onclick="openAssignModal({{ $serviceRequest->id }}, {{ $serviceRequest->service->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                Assigner
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Actions Technicien -->
                                    @if(Auth::user()->role === 'technicien' && $serviceRequest->technicien_id === Auth::id())
                                        @if($serviceRequest->status === 'assignee')
                                            <form method="POST" action="{{ route('service-requests.start', $serviceRequest) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                    Commencer
                                                </button>
                                            </form>
                                        @elseif($serviceRequest->status === 'en_cours')
                                            <button onclick="openCompleteModal({{ $serviceRequest->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                                                Terminer
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Actions Client -->
                                    @if($serviceRequest->client_id === Auth::id())
                                        @if(in_array($serviceRequest->status, ['en_attente', 'assignee']))
                                            <a href="{{ route('service-requests.edit', $serviceRequest) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                Modifier
                                            </a>
                                        @endif
                                        
                                        @if(!in_array($serviceRequest->status, ['en_cours', 'terminee']))
                                            <form method="POST" action="{{ route('service-requests.destroy', $serviceRequest) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <!-- Planifier RDV -->
                                    @if(in_array($serviceRequest->status, ['assignee', 'en_cours']))
                                        <a href="{{ route('appointments.create', ['service_request' => $serviceRequest->id]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                                            Planifier RDV
                                        </a>
                                    @endif
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande de service</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(Auth::user()->role === 'client')
                                    Commencez par faire votre première demande de service.
                                @else
                                    Aucune demande n'a été soumise pour le moment.
                                @endif
                            </p>
                            @if(Auth::user()->role === 'client')
                                <div class="mt-6">
                                    <a href="{{ route('service-requests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Demander un service
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($serviceRequests->hasPages())
                <div class="mt-8">
                    {{ $serviceRequests->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal d'assignation (pour admin) -->
    @if(Auth::user()->role === 'admin')
        <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Assigner un technicien</h3>
                    <form id="assignForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="technicien_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Technicien
                            </label>
                            <select id="technicien_id" name="technicien_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Chargement...</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAssignModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Assigner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de fin de service (pour technicien) -->
    @if(Auth::user()->role === 'technicien')
        <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Terminer le service</h3>
                    <form id="completeForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes de fin (optionnel)
                            </label>
                            <textarea id="completion_notes" name="completion_notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Travaux effectués, recommandations..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCompleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Terminer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        @if(Auth::user()->role === 'admin')
        function openAssignModal(serviceRequestId, serviceId) {
            document.getElementById('assignForm').action = `/service-requests/${serviceRequestId}/assign`;
            
            // Charger les techniciens disponibles
            fetch(`/services/${serviceId}/technicians`)
                .then(response => response.json())
                .then(technicians => {
                    const select = document.getElementById('technicien_id');
                    select.innerHTML = '<option value="">Sélectionnez un technicien</option>';
                    technicians.forEach(tech => {
                        select.innerHTML += `<option value="${tech.id}">${tech.name} - ${tech.speciality || 'Généraliste'}</option>`;
                    });
                });
            
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
        @endif

        @if(Auth::user()->role === 'technicien')
        function openCompleteModal(serviceRequestId) {
            document.getElementById('completeForm').action = `/service-requests/${serviceRequestId}/complete`;
            document.getElementById('completeModal').classList.remove('hidden');
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }
        @endif
    </script>
</x-app-layout> 