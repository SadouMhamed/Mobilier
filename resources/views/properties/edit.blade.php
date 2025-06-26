<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier l\'annonce') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Type d'annonce -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="type" :value="__('Type d\'annonce')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir le type</option>
                                    <option value="vente" {{ old('type', $property->type) == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="location" {{ old('type', $property->type) == 'location' ? 'selected' : '' }}>Location</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="property_type" :value="__('Type de bien')" />
                                <select id="property_type" name="property_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Choisir le type de bien</option>
                                    <option value="appartement" {{ old('property_type', $property->property_type) == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                    <option value="maison" {{ old('property_type', $property->property_type) == 'maison' ? 'selected' : '' }}>Maison</option>
                                    <option value="studio" {{ old('property_type', $property->property_type) == 'studio' ? 'selected' : '' }}>Studio</option>
                                    <option value="bureau" {{ old('property_type', $property->property_type) == 'bureau' ? 'selected' : '' }}>Bureau</option>
                                    <option value="terrain" {{ old('property_type', $property->property_type) == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                    <option value="local" {{ old('property_type', $property->property_type) == 'local' ? 'selected' : '' }}>Local commercial</option>
                                </select>
                                <x-input-error :messages="$errors->get('property_type')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Titre -->
                        <div>
                            <x-input-label for="title" :value="__('Titre de l\'annonce')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $property->title)" required placeholder="Ex: Bel appartement 3 pièces centre-ville" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Décrivez votre bien en détail (minimum 50 caractères)">{{ old('description', $property->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Prix et détails -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="price" :value="__('Prix (DZD)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $property->price)" required min="0" step="1" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="surface" :value="__('Surface (m²)')" />
                                <x-text-input id="surface" class="block mt-1 w-full" type="number" name="surface" :value="old('surface', $property->surface)" min="1" />
                                <x-input-error :messages="$errors->get('surface')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="rooms" :value="__('Nombre de pièces')" />
                                <x-text-input id="rooms" class="block mt-1 w-full" type="number" name="rooms" :value="old('rooms', $property->rooms)" min="1" />
                                <x-input-error :messages="$errors->get('rooms')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="bathrooms" :value="__('Salles de bain')" />
                                <x-text-input id="bathrooms" class="block mt-1 w-full" type="number" name="bathrooms" :value="old('bathrooms', $property->bathrooms)" min="1" />
                                <x-input-error :messages="$errors->get('bathrooms')" class="mt-2" />
                            </div>

                            <div class="flex items-center mt-6">
                                <input id="furnished" name="furnished" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                                <label for="furnished" class="ml-2 block text-sm text-gray-900">
                                    Meublé
                                </label>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Adresse')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $property->address)" required placeholder="Numéro et nom de rue" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" :value="__('Code postal')" />
                                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $property->postal_code)" required maxlength="5" pattern="[0-9]{5}" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('Ville')" />
                            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $property->city)" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <!-- Images actuelles -->
                        @if($property->images && count($property->images) > 0)
                            <div>
                                <x-input-label :value="__('Images actuelles')" />
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($property->images as $image)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Image actuelle" class="w-full h-24 object-cover rounded">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Pour changer les images, téléchargez de nouvelles photos ci-dessous (les anciennes seront remplacées).</p>
                            </div>
                        @endif

                        <!-- Nouvelles images -->
                        <div>
                            <x-input-label for="images" :value="__('Nouvelles photos (maximum 10) - Facultatif')" />
                            <input id="images" class="block mt-1 w-full" type="file" name="images[]" multiple accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, WebP. Taille maximum: 2MB par image. Laissez vide pour conserver les images actuelles.</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <!-- Commentaire admin (pour admin seulement) -->
                        @if(Auth::user()->role === 'admin')
                            <div>
                                <x-input-label for="admin_comment" :value="__('Commentaire admin')" />
                                <textarea id="admin_comment" name="admin_comment" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Commentaire visible par le propriétaire">{{ old('admin_comment', $property->admin_comment) }}</textarea>
                                <x-input-error :messages="$errors->get('admin_comment')" class="mt-2" />
                            </div>
                        @endif

                        <!-- Boutons -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <a href="{{ route('properties.show', $property) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 