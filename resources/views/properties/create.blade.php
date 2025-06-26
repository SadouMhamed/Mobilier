<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une annonce') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Type d'annonce -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="type" :value="__('Type d\'annonce')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir le type</option>
                                    <option value="vente" {{ old('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="location" {{ old('type') == 'location' ? 'selected' : '' }}>Location</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="property_type" :value="__('Type de bien')" />
                                <select id="property_type" name="property_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir le type de bien</option>
                                    <option value="appartement" {{ old('property_type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                    <option value="maison" {{ old('property_type') == 'maison' ? 'selected' : '' }}>Maison</option>
                                    <option value="studio" {{ old('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                                    <option value="bureau" {{ old('property_type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                                    <option value="terrain" {{ old('property_type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                    <option value="local" {{ old('property_type') == 'local' ? 'selected' : '' }}>Local commercial</option>
                                </select>
                                <x-input-error :messages="$errors->get('property_type')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Titre -->
                        <div>
                            <x-input-label for="title" :value="__('Titre de l\'annonce')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required placeholder="Ex: Bel appartement 3 pièces centre-ville" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Décrivez votre bien en détail (minimum 50 caractères)">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Prix et détails -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="price" :value="__('Prix (DZD)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required min="0" step="1" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="surface" :value="__('Surface (m²)')" />
                                <x-text-input id="surface" class="block mt-1 w-full" type="number" name="surface" :value="old('surface')" min="1" />
                                <x-input-error :messages="$errors->get('surface')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="rooms" :value="__('Nombre de pièces')" />
                                <x-text-input id="rooms" class="block mt-1 w-full" type="number" name="rooms" :value="old('rooms')" min="1" />
                                <x-input-error :messages="$errors->get('rooms')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="bathrooms" :value="__('Salles de bain')" />
                                <x-text-input id="bathrooms" class="block mt-1 w-full" type="number" name="bathrooms" :value="old('bathrooms')" min="1" />
                                <x-input-error :messages="$errors->get('bathrooms')" class="mt-2" />
                            </div>

                            <div class="flex items-center mt-6">
                                <input id="furnished" name="furnished" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('furnished') ? 'checked' : '' }}>
                                <label for="furnished" class="ml-2 block text-sm text-gray-900">
                                    Meublé
                                </label>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Adresse')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required placeholder="Numéro et nom de rue" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" :value="__('Code postal')" />
                                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required maxlength="5" pattern="[0-9]{5}" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('Ville')" />
                            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <!-- Images -->
                        <div>
                            <x-input-label for="images" :value="__('Photos (maximum 10)')" />
                            <input id="images" class="block mt-1 w-full" type="file" name="images[]" multiple accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, WebP. Taille maximum: 2MB par image.</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <!-- Boutons -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <a href="{{ route('properties.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Publier l\'annonce') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 