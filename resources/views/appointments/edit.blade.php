<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le rendez-vous') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Service Request (lecture seule) -->
                        <div>
                            <x-input-label for="service_request" :value="__('Demande de service')" />
                            <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                <p class="text-sm font-medium text-gray-900">{{ $appointment->serviceRequest->service->name }}</p>
                                <p class="text-xs text-gray-600">Demande #{{ $appointment->serviceRequest->id }} - {{ $appointment->serviceRequest->client->name }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->serviceRequest->address }}, {{ $appointment->serviceRequest->city }}</p>
                            </div>
                        </div>

                        <!-- Date et heure -->
                        <div>
                            <x-input-label for="scheduled_at" :value="__('Date et heure du rendez-vous')" />
                            <input id="scheduled_at" name="scheduled_at" type="datetime-local" 
                                value="{{ old('scheduled_at', $appointment->scheduled_at->format('Y-m-d\TH:i')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                required />
                            <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-600">Choisissez une date et heure future</p>
                        </div>

                        <!-- Durée -->
                        <div>
                            <x-input-label for="duration" :value="__('Durée estimée (en minutes)')" />
                            <select id="duration" name="duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Sélectionnez une durée</option>
                                <option value="30" {{ old('duration', $appointment->duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="60" {{ old('duration', $appointment->duration) == 60 ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('duration', $appointment->duration) == 90 ? 'selected' : '' }}>1h30</option>
                                <option value="120" {{ old('duration', $appointment->duration) == 120 ? 'selected' : '' }}>2 heures</option>
                                <option value="180" {{ old('duration', $appointment->duration) == 180 ? 'selected' : '' }}>3 heures</option>
                                <option value="240" {{ old('duration', $appointment->duration) == 240 ? 'selected' : '' }}>4 heures</option>
                                <option value="480" {{ old('duration', $appointment->duration) == 480 ? 'selected' : '' }}>Journée complète (8h)</option>
                            </select>
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes (optionnel)')" />
                            <textarea id="notes" name="notes" rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Instructions particulières, accès, matériel nécessaire...">{{ old('notes', $appointment->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Statut actuel -->
                        <div>
                            <x-input-label :value="__('Statut actuel')" />
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium
                                    @if($appointment->status == 'planifie') bg-blue-100 text-blue-800
                                    @elseif($appointment->status == 'confirme') bg-green-100 text-green-800
                                    @elseif($appointment->status == 'termine') bg-gray-100 text-gray-800
                                    @elseif($appointment->status == 'annule') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Information sur la modification -->
                        @if($appointment->status === 'confirme')
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
                                            <p>Ce rendez-vous a été confirmé par le technicien. La modification remettra le statut en "Planifié" et nécessitera une nouvelle confirmation.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Boutons -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('appointments.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </a>

                            <div class="space-x-2">
                                @if($appointment->status === 'planifie' || $appointment->status === 'confirme')
                                    <x-primary-button>
                                        {{ __('Mettre à jour') }}
                                    </x-primary-button>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 