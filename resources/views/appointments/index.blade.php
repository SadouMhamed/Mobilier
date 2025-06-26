<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(Auth::user()->role === 'admin')
                    Gestion des Rendez-vous
                @elseif(Auth::user()->role === 'technicien')
                    Mes Rendez-vous
                @else
                    Mes Rendez-vous
                @endif
            </h2>
            <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Planifier un rendez-vous
            </a>
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

            <!-- Filtres de statut -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap space-x-2 space-y-2 items-center">
                        <a href="{{ route('appointments.index', ['status' => 'actifs']) }}" 
                           class="px-4 py-2 rounded-lg {{ $statusFilter === 'actifs' ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                            📅 Actifs
                        </a>
                        <a href="{{ route('appointments.index', ['status' => 'termines']) }}" 
                           class="px-4 py-2 rounded-lg {{ $statusFilter === 'termines' ? 'bg-gray-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                            ✅ Terminés
                        </a>
                        <a href="{{ route('appointments.index', ['status' => 'annules']) }}" 
                           class="px-4 py-2 rounded-lg {{ $statusFilter === 'annules' ? 'bg-red-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                            ❌ Annulés
                        </a>
                        <a href="{{ route('appointments.index', ['status' => 'tous']) }}" 
                           class="px-4 py-2 rounded-lg {{ $statusFilter === 'tous' ? 'bg-purple-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                            📋 Tous
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des rendez-vous -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($appointments as $appointment)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $appointment->serviceRequest->service->name }}
                                        </h3>
                                        
                                        <!-- Badge statut -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($appointment->status == 'planifie') bg-blue-100 text-blue-800
                                            @elseif($appointment->status == 'confirme') bg-green-100 text-green-800
                                            @elseif($appointment->status == 'termine') bg-gray-100 text-gray-800
                                            @elseif($appointment->status == 'annule') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>

                                    <!-- Client (pour admin/technicien) -->
                                    @if(Auth::user()->role !== 'client')
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Client:</strong> {{ $appointment->client->name }}
                                        </p>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                        <div>
                                            <strong>📅 Date:</strong> {{ $appointment->scheduled_at->format('d/m/Y à H:i') }}
                                        </div>
                                        <div>
                                            <strong>⏱️ Durée:</strong> {{ $appointment->duration }} minutes
                                        </div>
                                        <div>
                                            <strong>📍 Lieu:</strong> {{ $appointment->serviceRequest->city }}
                                        </div>
                                    </div>

                                    @if($appointment->notes)
                                        <div class="text-sm text-gray-600 mt-2">
                                            <strong>Notes:</strong> {{ $appointment->notes }}
                                        </div>
                                    @endif
                                </div>

                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-blue-600">
                                        {{ number_format($appointment->serviceRequest->service->price, 0, ',', ' ') }} DZD
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $appointment->scheduled_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-4 border-t">
                                <div class="flex space-x-2">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails
                                    </a>
                                    <a href="{{ route('appointments.pdf', $appointment) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        PDF
                                    </a>
                                </div>

                                <div class="flex space-x-2">
                                    <!-- Actions Technicien -->
                                    @if(Auth::user()->role === 'technicien' && $appointment->serviceRequest->technicien_id === Auth::id())
                                        @if($appointment->status === 'planifie')
                                            <form method="POST" action="{{ route('appointments.confirm', $appointment) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                    Confirmer
                                                </button>
                                            </form>
                                        @elseif($appointment->status === 'confirme')
                                            <button onclick="openCompleteModal({{ $appointment->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                                                Terminer
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Actions Client/Admin -->
                                    @if($appointment->client_id === Auth::id() || Auth::user()->role === 'admin')
                                        @if($appointment->status !== 'termine' && $appointment->scheduled_at > now()->addHours(2))
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                Modifier
                                            </a>
                                        @endif
                                        
                                        @if($appointment->status !== 'termine' && $appointment->status !== 'annule')
                                            <button onclick="openCancelModal({{ $appointment->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                Annuler
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h3m12 0a2 2 0 012 2v8a2 2 0 01-2 2h-3a2 2 0 01-2-2v-8a2 2 0 012-2h3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Vous n'avez pas encore de rendez-vous planifiés.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Planifier un rendez-vous
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="mt-8">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de fin de rendez-vous -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Terminer le rendez-vous</h3>
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes de fin (optionnel)
                        </label>
                        <textarea id="notes" name="notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Travaux effectués, recommandations..."></textarea>
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

    <!-- Modal d'annulation -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Annuler le rendez-vous</h3>
                <form id="cancelForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Raison de l'annulation *
                        </label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Expliquez pourquoi vous annulez ce rendez-vous..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCancelModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Retour
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Annuler le RDV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCompleteModal(appointmentId) {
            document.getElementById('completeForm').action = `/appointments/${appointmentId}/complete`;
            document.getElementById('completeModal').classList.remove('hidden');
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }

        function openCancelModal(appointmentId) {
            document.getElementById('cancelForm').action = `/appointments/${appointmentId}/cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-app-layout> 