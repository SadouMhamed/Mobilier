<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demander un service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-6">
                        @csrf

                        <!-- Service -->
                        <div>
                            <x-input-label for="service_id" :value="__('Type de service')" />
                            <select id="service_id" name="service_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Sélectionnez un service</option>
                                                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} DZD
                            </option>
                        @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description détaillée')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Décrivez précisément le problème ou les travaux à effectuer (minimum 20 caractères)">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Priorité et Date préférée -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="priority" :value="__('Priorité')" />
                                <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir la priorité</option>
                                    <option value="basse" {{ old('priority') == 'basse' ? 'selected' : '' }}>🟢 Basse</option>
                                    <option value="normale" {{ old('priority') == 'normale' ? 'selected' : '' }}>🟡 Normale</option>
                                    <option value="haute" {{ old('priority') == 'haute' ? 'selected' : '' }}>🟠 Haute</option>
                                    <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="preferred_date" :value="__('Date préférée (optionnel)')" />
                                <x-text-input id="preferred_date" class="block mt-1 w-full" type="datetime-local" name="preferred_date" :value="old('preferred_date')" />
                                <p class="mt-1 text-sm text-gray-500">Laissez vide si vous n'avez pas de préférence</p>
                                <x-input-error :messages="$errors->get('preferred_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📍 Adresse d'intervention</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="address" :value="__('Adresse')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required placeholder="Numéro et nom de rue" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="postal_code" :value="__('Code postal (optionnel)')" />
                                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" maxlength="5" pattern="[0-9]{5}" />
                                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <x-input-label for="city" :value="__('Ville')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('Téléphone (optionnel)')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" placeholder="06 12 34 56 78" />
                                    <p class="mt-1 text-sm text-gray-500">Pour être contacté rapidement</p>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Informations sur le processus -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">📋 Processus de traitement</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Votre demande sera examinée par notre équipe</li>
                                <li>• Un technicien qualifié vous sera assigné</li>
                                <li>• Vous pourrez planifier un rendez-vous</li>
                                <li>• Le technicien interviendra à l'heure convenue</li>
                            </ul>
                        </div>

                        <!-- Boutons -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <a href="{{ route('service-requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Soumettre la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-remplir l'adresse du profil utilisateur si disponible
        document.addEventListener('DOMContentLoaded', function() {
            @if(Auth::user()->address)
                if (!document.getElementById('address').value) {
                    const userAddress = @json(Auth::user()->address);
                    document.getElementById('address').value = userAddress;
                }
            @endif
        });
    </script>
</x-app-layout> 