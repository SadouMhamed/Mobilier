<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planifier un rendez-vous') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Type de rendez-vous -->
                        <div>
                            <x-input-label :value="__('Type de rendez-vous')" />
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="appointment_type" value="existing_request" 
                                           class="mr-2" {{ old('appointment_type', 'existing_request') === 'existing_request' ? 'checked' : '' }}
                                           onchange="toggleAppointmentType()">
                                    <span>📋 Rendez-vous pour une demande existante</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="appointment_type" value="direct_service" 
                                           class="mr-2" {{ old('appointment_type') === 'direct_service' ? 'checked' : '' }}
                                           onchange="toggleAppointmentType()">
                                    <span>🛠️ Nouveau rendez-vous avec sélection de service</span>
                                </label>
                            </div>
                        </div>

                        <!-- Demande de service existante -->
                        <div id="existing_request_section" class="existing-section">
                            <x-input-label for="service_request_id" :value="__('Demande de service existante')" />
                            <select id="service_request_id" name="service_request_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Sélectionnez une demande de service</option>
                                @foreach($serviceRequests as $serviceRequest)
                                    <option value="{{ $serviceRequest->id }}" 
                                            {{ (request('service_request') == $serviceRequest->id || old('service_request_id') == $serviceRequest->id) ? 'selected' : '' }}>
                                        {{ $serviceRequest->service->name }} - 
                                        @if(Auth::user()->role === 'admin')
                                            {{ $serviceRequest->client->name }} ({{ $serviceRequest->city }})
                                        @elseif(Auth::user()->role === 'technicien')
                                            {{ $serviceRequest->client->name }} ({{ $serviceRequest->city }})
                                        @else
                                            {{ $serviceRequest->city }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_request_id')" class="mt-2" />
                        </div>

                        <!-- Nouveau rendez-vous avec service direct -->
                        <div id="direct_service_section" class="direct-section hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="service_id" :value="__('Service')" />
                                    <select id="service_id" name="service_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Sélectionnez un service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} DZD
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                                </div>

                                @if(Auth::user()->role === 'admin')
                                <div>
                                    <x-input-label for="client_id" :value="__('Client')" />
                                    <select id="client_id" name="client_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Sélectionnez un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                </div>
                                @endif
                            </div>

                            <!-- Informations du service -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="address" :value="__('Adresse d\'intervention')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" placeholder="Adresse complète" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="city" :value="__('Ville')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" placeholder="Ville" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description du problème')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Décrivez le problème ou la demande...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Date et heure -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="scheduled_at" :value="__('Date et heure du rendez-vous')" />
                                <x-text-input id="scheduled_at" class="block mt-1 w-full" type="datetime-local" name="scheduled_at" :value="old('scheduled_at')" required />
                                <p class="mt-1 text-sm text-gray-500">Le rendez-vous doit être planifié dans le futur</p>
                                <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duration" :value="__('Durée estimée (minutes)')" />
                                <select id="duration" name="duration" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir la durée</option>
                                    <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 heure</option>
                                    <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>1h30</option>
                                    <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 heures</option>
                                    <option value="180" {{ old('duration') == '180' ? 'selected' : '' }}>3 heures</option>
                                    <option value="240" {{ old('duration') == '240' ? 'selected' : '' }}>4 heures</option>
                                    <option value="360" {{ old('duration') == '360' ? 'selected' : '' }}>6 heures</option>
                                    <option value="480" {{ old('duration') == '480' ? 'selected' : '' }}>8 heures</option>
                                </select>
                                <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes (optionnel)')" />
                            <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Instructions particulières, matériel nécessaire, etc.">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Informations sur le processus -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">📅 Informations importantes</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Le rendez-vous sera en statut "Planifié" après création</li>
                                <li>• Le technicien assigné devra confirmer le rendez-vous</li>
                                <li>• Vous recevrez une notification de confirmation</li>
                                <li>• Le rendez-vous peut être modifié jusqu'à 2h avant l'heure prévue</li>
                            </ul>
                        </div>

                        <!-- Détails de la demande sélectionnée -->
                        <div id="serviceDetails" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">📋 Détails de la demande</h4>
                            <div id="serviceDetailsContent"></div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <a href="{{ route('appointments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Planifier le rendez-vous') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Basculer entre les types de rendez-vous
        function toggleAppointmentType() {
            const appointmentType = document.querySelector('input[name="appointment_type"]:checked').value;
            const existingSection = document.getElementById('existing_request_section');
            const directSection = document.getElementById('direct_service_section');
            const serviceRequestSelect = document.getElementById('service_request_id');
            const serviceSelect = document.getElementById('service_id');
            
            if (appointmentType === 'existing_request') {
                existingSection.classList.remove('hidden');
                directSection.classList.add('hidden');
                serviceRequestSelect.required = true;
                serviceSelect.required = false;
                // Nettoyer les champs du service direct
                document.getElementById('service_id').value = '';
                if (document.getElementById('client_id')) {
                    document.getElementById('client_id').value = '';
                }
                document.getElementById('address').value = '';
                document.getElementById('city').value = '';
                document.getElementById('description').value = '';
            } else {
                existingSection.classList.add('hidden');
                directSection.classList.remove('hidden');
                serviceRequestSelect.required = false;
                serviceSelect.required = true;
                // Nettoyer la sélection de demande existante
                document.getElementById('service_request_id').value = '';
            }
        }

        // Afficher les détails de la demande sélectionnée
        document.getElementById('service_request_id').addEventListener('change', function() {
            const serviceRequestId = this.value;
            const detailsDiv = document.getElementById('serviceDetails');
            const detailsContent = document.getElementById('serviceDetailsContent');
            
            if (serviceRequestId) {
                // Ici vous pourriez faire un appel AJAX pour récupérer les détails
                // Pour l'instant, on affiche juste un message
                detailsContent.innerHTML = `
                    <p class="text-sm text-gray-600">
                        Demande sélectionnée : ID ${serviceRequestId}
                    </p>
                `;
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        });

        // Définir une heure minimum (maintenant + 1 heure)
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            now.setHours(now.getHours() + 1);
            const minDateTime = now.toISOString().slice(0, 16);
            document.getElementById('scheduled_at').min = minDateTime;
            
            // Initialiser l'affichage selon le type sélectionné
            toggleAppointmentType();
        });
    </script>
</x-app-layout> 