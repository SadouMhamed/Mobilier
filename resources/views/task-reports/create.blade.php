<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajouter un rapport de tâche - {{ $serviceRequest->service->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Info de la demande -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informations de la demande</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="font-medium">Client :</span> {{ $serviceRequest->client->name }}
                        </div>
                        <div>
                            <span class="font-medium">Service :</span> {{ $serviceRequest->service->name }}
                        </div>
                        <div>
                            <span class="font-medium">Adresse :</span> 
                            {{ $serviceRequest->address }}, {{ $serviceRequest->city }} {{ $serviceRequest->postal_code }}
                        </div>
                        <div>
                            <span class="font-medium">Téléphone :</span> {{ $serviceRequest->phone ?? 'Non renseigné' }}
                        </div>
                        <div class="col-span-2">
                            <span class="font-medium">Description :</span> {{ $serviceRequest->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de rapport -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Rapport d'intervention</h3>

                    <form method="POST" action="{{ route('task-reports.store', $serviceRequest) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Titre de la tâche -->
                        <div class="mb-6">
                            <x-input-label for="task_title" value="Titre de la tâche" />
                            <x-text-input id="task_title" name="task_title" type="text" class="mt-1 block w-full" 
                                :value="old('task_title')" required maxlength="255" 
                                placeholder="Ex: Réparation fuite évier cuisine" />
                            <x-input-error class="mt-2" :messages="$errors->get('task_title')" />
                        </div>

                        <!-- Description détaillée -->
                        <div class="mb-6">
                            <x-input-label for="task_description" value="Description détaillée de l'intervention" />
                            <textarea id="task_description" name="task_description" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="4" required maxlength="2000" 
                                placeholder="Décrivez en détail les tâches effectuées, les problèmes rencontrés et les solutions apportées...">{{ old('task_description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('task_description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Durée -->
                            <div>
                                <x-input-label for="duration_minutes" value="Durée d'intervention (minutes)" />
                                <x-text-input id="duration_minutes" name="duration_minutes" type="number" 
                                    class="mt-1 block w-full" :value="old('duration_minutes')" 
                                    min="15" max="1440" step="15" 
                                    placeholder="Ex: 120 pour 2h" />
                                <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Durée en minutes (ex: 120 pour 2 heures)
                                </p>
                            </div>

                            <!-- Coût matériaux -->
                            <div>
                                <x-input-label for="material_cost" value="Coût des matériaux (DZD)" />
                                <x-text-input id="material_cost" name="material_cost" type="number" 
                                    class="mt-1 block w-full" :value="old('material_cost', 0)" 
                                    min="0" max="999999" step="1" 
                                    placeholder="Ex: 5000" />
                                <x-input-error class="mt-2" :messages="$errors->get('material_cost')" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Laisser à 0 si aucun matériau utilisé
                                </p>
                            </div>
                        </div>

                        <!-- Matériaux utilisés -->
                        <div class="mb-6">
                            <x-input-label for="materials_used" value="Matériaux utilisés (optionnel)" />
                            <textarea id="materials_used" name="materials_used" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000" 
                                placeholder="Liste des matériaux et pièces utilisés...">{{ old('materials_used') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('materials_used')" />
                        </div>

                        <!-- Niveau de difficulté -->
                        <div class="mb-6">
                            <x-input-label for="difficulty" value="Niveau de difficulté" />
                            <select id="difficulty" name="difficulty" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                required>
                                <option value="">Sélectionner un niveau</option>
                                <option value="facile" {{ old('difficulty') == 'facile' ? 'selected' : '' }}>
                                    Facile - Intervention simple
                                </option>
                                <option value="normale" {{ old('difficulty') == 'normale' ? 'selected' : '' }}>
                                    Normale - Intervention standard
                                </option>
                                <option value="difficile" {{ old('difficulty') == 'difficile' ? 'selected' : '' }}>
                                    Difficile - Intervention complexe
                                </option>
                                <option value="complexe" {{ old('difficulty') == 'complexe' ? 'selected' : '' }}>
                                    Complexe - Intervention exceptionnelle
                                </option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('difficulty')" />
                        </div>

                        <!-- Photos avant -->
                        <div class="mb-6">
                            <x-input-label for="before_photos" value="Photos avant intervention (optionnel)" />
                            <input id="before_photos" name="before_photos[]" type="file" 
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                                accept="image/*" multiple />
                            <x-input-error class="mt-2" :messages="$errors->get('before_photos')" />
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Formats acceptés : JPG, PNG, GIF. Taille max : 2MB par photo.
                            </p>
                        </div>

                        <!-- Photos après -->
                        <div class="mb-6">
                            <x-input-label for="after_photos" value="Photos après intervention (optionnel)" />
                            <input id="after_photos" name="after_photos[]" type="file" 
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                                accept="image/*" multiple />
                            <x-input-error class="mt-2" :messages="$errors->get('after_photos')" />
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Formats acceptés : JPG, PNG, GIF. Taille max : 2MB par photo.
                            </p>
                        </div>

                        <!-- Observations -->
                        <div class="mb-6">
                            <x-input-label for="observations" value="Observations particulières (optionnel)" />
                            <textarea id="observations" name="observations" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000" 
                                placeholder="Problèmes rencontrés, points d'attention, améliorations suggérées...">{{ old('observations') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('observations')" />
                        </div>

                        <!-- Recommandations -->
                        <div class="mb-6">
                            <x-input-label for="recommendations" value="Recommandations pour le client (optionnel)" />
                            <textarea id="recommendations" name="recommendations" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000" 
                                placeholder="Conseils d'entretien, prochaines interventions à prévoir, etc...">{{ old('recommendations') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('recommendations')" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('service-requests.show', $serviceRequest) }}" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                Enregistrer le rapport
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>