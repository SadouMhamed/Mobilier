<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Rapport de tâche - {{ $taskReport->task_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations de la demande -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informations de la demande</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="font-medium">Client :</span> {{ $taskReport->serviceRequest->client->name }}
                        </div>
                        <div>
                            <span class="font-medium">Service :</span> {{ $taskReport->serviceRequest->service->name }}
                        </div>
                        <div>
                            <span class="font-medium">Technicien :</span> {{ $taskReport->serviceRequest->technicien->name }}
                        </div>
                        <div class="col-span-3">
                            <span class="font-medium">Adresse :</span> 
                            {{ $taskReport->serviceRequest->address }}, {{ $taskReport->serviceRequest->city }} {{ $taskReport->serviceRequest->postal_code }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du rapport -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-semibold">Détails du rapport</h3>
                        <div class="flex space-x-2">
                            @if(Auth::user()->role === 'technicien' && Auth::id() === $taskReport->serviceRequest->technicien_id && !$taskReport->serviceRequest->invoice)
                                <a href="{{ route('task-reports.edit', $taskReport) }}" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Modifier
                                </a>
                            @endif
                            @if(Auth::user()->role === 'admin' && !$taskReport->serviceRequest->invoice)
                                <a href="{{ route('invoices.create', $taskReport->serviceRequest) }}" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Créer une facture
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Informations principales -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Titre de la tâche</h4>
                                <p class="text-gray-900 dark:text-gray-100">{{ $taskReport->task_title }}</p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Description de l'intervention</h4>
                                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $taskReport->task_description }}</p>
                            </div>
                        </div>

                        <!-- Détails techniques -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Durée</h4>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $taskReport->formatted_duration }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Coût matériaux</h4>
                                    <p class="text-gray-900 dark:text-gray-100">
                                        {{ number_format($taskReport->material_cost, 0, ',', ' ') }} DZD
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Niveau de difficulté</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $taskReport->difficulty_color }}">
                                    {{ $taskReport->difficulty_text }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Date du rapport</h4>
                                <p class="text-gray-900 dark:text-gray-100">{{ $taskReport->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Photos avant -->
                    @if($taskReport->photos_before)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Photos avant intervention</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($taskReport->photos_before as $photo)
                                    <div class="aspect-square">
                                        <img src="{{ Storage::url($photo) }}" 
                                            alt="Photo avant" 
                                            class="w-full h-full object-cover rounded-lg shadow-md cursor-pointer hover:opacity-75"
                                            onclick="openImageModal('{{ Storage::url($photo) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Photos après -->
                    @if($taskReport->photos_after)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Photos après intervention</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($taskReport->photos_after as $photo)
                                    <div class="aspect-square">
                                        <img src="{{ Storage::url($photo) }}" 
                                            alt="Photo après" 
                                            class="w-full h-full object-cover rounded-lg shadow-md cursor-pointer hover:opacity-75"
                                            onclick="openImageModal('{{ Storage::url($photo) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Observations -->
                    @if($taskReport->observations)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Observations particulières</h4>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $taskReport->observations }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Recommandations -->
                    @if($taskReport->recommendations)
                        <div class="mt-8">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Recommandations pour le client</h4>
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $taskReport->recommendations }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status de facturation -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Status de facturation</h4>
                        @if($taskReport->serviceRequest->invoice)
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Facturé
                                </span>
                                <a href="{{ route('invoices.show', $taskReport->serviceRequest->invoice) }}" 
                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Voir la facture #{{ $taskReport->serviceRequest->invoice->invoice_number }}
                                </a>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                En attente de facturation
                            </span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('service-requests.show', $taskReport->serviceRequest) }}" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les images en grand -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white text-2xl font-bold hover:text-gray-300 z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
    </div>

    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Fermer le modal en cliquant en dehors de l'image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Fermer le modal avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>