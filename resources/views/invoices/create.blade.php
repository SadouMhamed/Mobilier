<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Créer une facture - {{ $serviceRequest->service->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations de la demande -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informations de la demande</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="font-medium">Client :</span> {{ $serviceRequest->client->name }}
                        </div>
                        <div>
                            <span class="font-medium">Email :</span> {{ $serviceRequest->client->email }}
                        </div>
                        <div>
                            <span class="font-medium">Service :</span> {{ $serviceRequest->service->name }}
                        </div>
                        <div>
                            <span class="font-medium">Technicien :</span> {{ $serviceRequest->technicien->name }}
                        </div>
                        <div class="col-span-2">
                            <span class="font-medium">Adresse :</span> 
                            {{ $serviceRequest->address }}, {{ $serviceRequest->city }} {{ $serviceRequest->postal_code }}
                        </div>
                        <div class="col-span-2">
                            <span class="font-medium">Description :</span> {{ $serviceRequest->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé des rapports de tâches -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Rapports de tâches</h3>
                    <div class="space-y-4">
                        @foreach($serviceRequest->taskReports as $report)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium">{{ $report->task_title }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->difficulty_color }}">
                                        {{ $report->difficulty_text }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ $report->task_description }}</p>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium">Durée :</span> {{ $report->formatted_duration }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Matériaux :</span> {{ number_format($report->material_cost, 0, ',', ' ') }} DZD
                                    </div>
                                    <div>
                                        <span class="font-medium">Date :</span> {{ $report->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <a href="{{ route('task-reports.show', $report) }}" 
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                    Voir le détail →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Formulaire de création de facture -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Détails de la facture</h3>

                    <form method="POST" action="{{ route('invoices.store', $serviceRequest) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Prix de base -->
                            <div>
                                <x-input-label for="base_amount" value="Prix de base du service (DZD)" />
                                <x-text-input id="base_amount" name="base_amount" type="number" 
                                    class="mt-1 block w-full" :value="old('base_amount', $baseAmount)" 
                                    required min="0" max="9999999" step="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('base_amount')" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Prix standard : {{ number_format($baseAmount, 0, ',', ' ') }} DZD
                                </p>
                            </div>

                            <!-- Montant supplémentaire -->
                            <div>
                                <x-input-label for="additional_amount" value="Montant supplémentaire (DZD)" />
                                <x-text-input id="additional_amount" name="additional_amount" type="number" 
                                    class="mt-1 block w-full" :value="old('additional_amount', $totalMaterialCost)" 
                                    min="0" max="9999999" step="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('additional_amount')" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Coût matériaux total : {{ number_format($totalMaterialCost, 0, ',', ' ') }} DZD
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Remise -->
                            <div>
                                <x-input-label for="discount_amount" value="Remise (DZD)" />
                                <x-text-input id="discount_amount" name="discount_amount" type="number" 
                                    class="mt-1 block w-full" :value="old('discount_amount', 0)" 
                                    min="0" max="9999999" step="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('discount_amount')" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Laisser à 0 si aucune remise
                                </p>
                            </div>

                            <!-- Échéance -->
                            <div>
                                <x-input-label for="due_days" value="Échéance (jours)" />
                                <select id="due_days" name="due_days" 
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                    required>
                                    <option value="7" {{ old('due_days') == '7' ? 'selected' : '' }}>7 jours</option>
                                    <option value="15" {{ old('due_days', '15') == '15' ? 'selected' : '' }}>15 jours</option>
                                    <option value="30" {{ old('due_days') == '30' ? 'selected' : '' }}>30 jours</option>
                                    <option value="45" {{ old('due_days') == '45' ? 'selected' : '' }}>45 jours</option>
                                    <option value="60" {{ old('due_days') == '60' ? 'selected' : '' }}>60 jours</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('due_days')" />
                            </div>
                        </div>

                        <!-- Calcul total dynamique -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold mb-4">Calcul du montant total</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Prix de base :</span>
                                    <span id="display-base">{{ number_format($baseAmount, 0, ',', ' ') }} DZD</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Montant supplémentaire :</span>
                                    <span id="display-additional">{{ number_format($totalMaterialCost, 0, ',', ' ') }} DZD</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Remise :</span>
                                    <span id="display-discount">0 DZD</span>
                                </div>
                                <hr class="border-gray-300 dark:border-gray-600">
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>Total :</span>
                                    <span id="display-total">{{ number_format($baseAmount + $totalMaterialCost, 0, ',', ' ') }} DZD</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes administratives -->
                        <div class="mb-6">
                            <x-input-label for="admin_notes" value="Notes administratives (optionnel)" />
                            <textarea id="admin_notes" name="admin_notes" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000" 
                                placeholder="Notes internes, commentaires pour l'équipe...">{{ old('admin_notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('admin_notes')" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('service-requests.show', $serviceRequest) }}" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                Créer la facture
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour formater les nombres
        function formatNumber(num) {
            return new Intl.NumberFormat('fr-FR').format(num);
        }

        // Fonction pour calculer et afficher le total
        function updateTotal() {
            const baseAmount = parseFloat(document.getElementById('base_amount').value) || 0;
            const additionalAmount = parseFloat(document.getElementById('additional_amount').value) || 0;
            const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
            
            const total = baseAmount + additionalAmount - discountAmount;
            
            document.getElementById('display-base').textContent = formatNumber(baseAmount) + ' DZD';
            document.getElementById('display-additional').textContent = formatNumber(additionalAmount) + ' DZD';
            document.getElementById('display-discount').textContent = formatNumber(discountAmount) + ' DZD';
            document.getElementById('display-total').textContent = formatNumber(total) + ' DZD';
        }

        // Écouter les changements sur les champs
        document.getElementById('base_amount').addEventListener('input', updateTotal);
        document.getElementById('additional_amount').addEventListener('input', updateTotal);
        document.getElementById('discount_amount').addEventListener('input', updateTotal);

        // Calculer le total initial
        updateTotal();
    </script>
</x-app-layout>