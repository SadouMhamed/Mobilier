<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la demande de service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Service -->
                        <div>
                            <x-input-label for="service_id" :value="__('Service demandé')" />
                            <select id="service_id" name="service_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Sélectionnez un service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $serviceRequest->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} DZD
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description détaillée')" />
                            <textarea id="description" name="description" rows="4" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Décrivez en détail votre demande, le problème rencontré, etc." required>{{ old('description', $serviceRequest->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Priorité -->
                        <div>
                            <x-input-label for="priority" :value="__('Priorité')" />
                            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="basse" {{ old('priority', $serviceRequest->priority) == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="normale" {{ old('priority', $serviceRequest->priority) == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ old('priority', $serviceRequest->priority) == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="urgente" {{ old('priority', $serviceRequest->priority) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Date préférée -->
                        <div>
                            <x-input-label for="preferred_date" :value="__('Date et heure préférées (optionnel)')" />
                            <input id="preferred_date" name="preferred_date" type="datetime-local" 
                                value="{{ old('preferred_date', $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('Y-m-d\TH:i') : '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error :messages="$errors->get('preferred_date')" class="mt-2" />
                        </div>

                        <!-- Adresse -->
                        <div>
                            <x-input-label for="address" :value="__('Adresse')" />
                            <x-text-input id="address" name="address" type="text" 
                                value="{{ old('address', $serviceRequest->address) }}"
                                class="mt-1 block w-full" placeholder="123 rue de la Paix" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Ville et Code postal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="city" :value="__('Ville')" />
                                <x-text-input id="city" name="city" type="text" 
                                    value="{{ old('city', $serviceRequest->city) }}"
                                    class="mt-1 block w-full" placeholder="Paris" required />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" :value="__('Code postal (optionnel)')" />
                                <x-text-input id="postal_code" name="postal_code" type="text" 
                                    value="{{ old('postal_code', $serviceRequest->postal_code) }}"
                                    class="mt-1 block w-full" placeholder="75001" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <x-input-label for="phone" :value="__('Numéro de téléphone (optionnel)')" />
                            <x-text-input id="phone" name="phone" type="tel" 
                                value="{{ old('phone', $serviceRequest->phone) }}"
                                class="mt-1 block w-full" placeholder="06 12 34 56 78" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Avertissement si déjà assignée -->
                        @if(in_array($serviceRequest->status, ['assignee', 'en_cours']))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Attention
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Cette demande a déjà été assignée à un technicien. La modifier remettra le statut en "En attente" et annulera l'assignation actuelle.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Boutons -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('service-requests.show', $serviceRequest) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>

                            <x-primary-button>
                                {{ __('Mettre à jour la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 