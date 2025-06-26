<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier le rapport - {{ $taskReport->task_title }}
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
                            <span class="font-medium">Client :</span> {{ $taskReport->serviceRequest->client->name }}
                        </div>
                        <div>
                            <span class="font-medium">Service :</span> {{ $taskReport->serviceRequest->service->name }}
                        </div>
                        <div class="col-span-2">
                            <span class="font-medium">Adresse :</span> 
                            {{ $taskReport->serviceRequest->address }}, {{ $taskReport->serviceRequest->city }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'édition -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Modifier le rapport</h3>

                    <form method="POST" action="{{ route('task-reports.update', $taskReport) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Titre de la tâche -->
                        <div class="mb-6">
                            <x-input-label for="task_title" value="Titre de la tâche" />
                            <x-text-input id="task_title" name="task_title" type="text" class="mt-1 block w-full" 
                                :value="old('task_title', $taskReport->task_title)" required maxlength="255" />
                            <x-input-error class="mt-2" :messages="$errors->get('task_title')" />
                        </div>

                        <!-- Description détaillée -->
                        <div class="mb-6">
                            <x-input-label for="task_description" value="Description détaillée de l'intervention" />
                            <textarea id="task_description" name="task_description" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="4" required maxlength="2000">{{ old('task_description', $taskReport->task_description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('task_description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Durée -->
                            <div>
                                <x-input-label for="duration_hours" value="Durée d'intervention (heures)" />
                                <x-text-input id="duration_hours" name="duration_hours" type="number" 
                                    class="mt-1 block w-full" :value="old('duration_hours', $taskReport->duration_hours)" 
                                    min="0.5" max="24" step="0.5" required />
                                <x-input-error class="mt-2" :messages="$errors->get('duration_hours')" />
                            </div>

                            <!-- Coût matériaux -->
                            <div>
                                <x-input-label for="material_cost" value="Coût des matériaux (DZD)" />
                                <x-text-input id="material_cost" name="material_cost" type="number" 
                                    class="mt-1 block w-full" :value="old('material_cost', $taskReport->material_cost)" 
                                    min="0" max="999999" step="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('material_cost')" />
                            </div>
                        </div>

                        <!-- Niveau de difficulté -->
                        <div class="mb-6">
                            <x-input-label for="difficulty_level" value="Niveau de difficulté" />
                            <select id="difficulty_level" name="difficulty_level" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                required>
                                <option value="">Sélectionner un niveau</option>
                                <option value="facile" {{ old('difficulty_level', $taskReport->difficulty_level) == 'facile' ? 'selected' : '' }}>Facile</option>
                                <option value="moyen" {{ old('difficulty_level', $taskReport->difficulty_level) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                <option value="difficile" {{ old('difficulty_level', $taskReport->difficulty_level) == 'difficile' ? 'selected' : '' }}>Difficile</option>
                                <option value="tres_difficile" {{ old('difficulty_level', $taskReport->difficulty_level) == 'tres_difficile' ? 'selected' : '' }}>Très difficile</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('difficulty_level')" />
                        </div>

                        <!-- Observations -->
                        <div class="mb-6">
                            <x-input-label for="observations" value="Observations particulières (optionnel)" />
                            <textarea id="observations" name="observations" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000">{{ old('observations', $taskReport->observations) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('observations')" />
                        </div>

                        <!-- Recommandations -->
                        <div class="mb-6">
                            <x-input-label for="recommendations" value="Recommandations pour le client (optionnel)" />
                            <textarea id="recommendations" name="recommendations" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                rows="3" maxlength="1000">{{ old('recommendations', $taskReport->recommendations) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('recommendations')" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('task-reports.show', $taskReport) }}" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                Mettre à jour le rapport
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
