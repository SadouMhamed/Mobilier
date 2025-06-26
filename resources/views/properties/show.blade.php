<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->title }}
            </h2>
            <div class="flex space-x-2">
                <!-- Bouton PDF -->
                <a href="{{ route('properties.pdf', $property) }}" 
                   class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                @if($property->user_id == Auth::id() || Auth::user()->role === 'admin')
                    <a href="{{ route('properties.edit', $property) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Modifier
                    </a>
                @endif
                <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statut et actions admin -->
            @if(Auth::user()->role === 'admin' && $property->status == 'en_attente')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-yellow-800">Annonce en attente de validation</h3>
                            <p class="text-sm text-yellow-600">Cette annonce nécessite votre validation pour être publiée.</p>
                        </div>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('properties.validate', $property) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                    Valider
                                </button>
                            </form>
                            <button onclick="openRejectModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Rejeter
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statut rejetée -->
            @if($property->status == 'rejetee' && $property->admin_comment)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-red-800 mb-2">Annonce rejetée</h3>
                    <p class="text-sm text-red-600">{{ $property->admin_comment }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Images et détails principaux -->
                <div class="lg:col-span-2">
                    <!-- Galerie d'images -->
                    <div class="bg-white rounded-lg shadow-sm border mb-6">
                        @if($property->images && count($property->images) > 0)
                            <div class="space-y-4 p-6">
                                <!-- Image principale -->
                                <div class="h-96 bg-gray-200 rounded-lg overflow-hidden">
                                    <img id="mainImage" src="{{ asset('storage/' . $property->images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Miniatures -->
                                @if(count($property->images) > 1)
                                    <div class="grid grid-cols-6 gap-2">
                                        @foreach($property->images as $index => $image)
                                            <button onclick="changeMainImage('{{ asset('storage/' . $image) }}')" class="h-16 bg-gray-200 rounded overflow-hidden hover:opacity-75">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Photo {{ $index + 1 }}" class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-96 bg-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-500">Aucune image disponible</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                        <div class="prose max-w-none">
                            {!! nl2br(e($property->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées -->
                <div class="space-y-6">
                    <!-- Prix et type -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-2">
                                {{ number_format($property->price, 0, ',', ' ') }} DZD
                            </div>
                            <div class="flex justify-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($property->type) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($property->property_type) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Caractéristiques -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Caractéristiques</h3>
                        <div class="space-y-3">
                            @if($property->surface)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Surface</span>
                                    <span class="font-medium">{{ $property->surface }} m²</span>
                                </div>
                            @endif
                            @if($property->rooms)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Pièces</span>
                                    <span class="font-medium">{{ $property->rooms }}</span>
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Salles de bain</span>
                                    <span class="font-medium">{{ $property->bathrooms }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Meublé</span>
                                <span class="font-medium">{{ $property->furnished ? 'Oui' : 'Non' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Localisation</h3>
                        <div class="space-y-2">
                            <p class="text-gray-600">{{ $property->address }}</p>
                            <p class="font-medium">{{ $property->city }} {{ $property->postal_code }}</p>
                        </div>
                    </div>

                    <!-- Propriétaire (pour admin) -->
                    @if(Auth::user()->role === 'admin')
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Propriétaire</h3>
                            <div class="space-y-2">
                                <p class="font-medium">{{ $property->user->name }}</p>
                                <p class="text-gray-600">{{ $property->user->email }}</p>
                                @if($property->user->phone)
                                    <p class="text-gray-600">{{ $property->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Prendre rendez-vous -->
                    @if($property->status === 'validee' && $property->user_id !== Auth::id())
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Organiser une visite</h3>
                            <button onclick="openAppointmentModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium mb-3">
                                📅 Prendre rendez-vous
                            </button>
                            <button onclick="openContactModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium">
                                💬 Contacter le propriétaire
                            </button>
                        </div>
                    @endif

                    <!-- Mes rendez-vous pour cette annonce (si propriétaire) -->
                    @if($property->user_id === Auth::id())
                        @php
                            $propertyAppointments = $property->propertyAppointments()
                                ->whereIn('status', ['pending', 'confirmed'])
                                ->with('client')
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @if($propertyAppointments->count() > 0)
                            <div class="bg-white rounded-lg shadow-sm border p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Demandes de visite</h3>
                                    <a href="{{ route('property-appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
                                </div>
                                
                                @foreach($propertyAppointments as $appointment)
                                    <div class="border-l-4 {{ $appointment->status === 'pending' ? 'border-yellow-500' : 'border-blue-500' }} pl-4 mb-3 last:mb-0">
                                        <p class="font-medium text-gray-900">{{ $appointment->client_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $appointment->requested_date->format('d/m/Y à H:i') }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                                            {{ $appointment->status_label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de rejet (pour admin) -->
    @if(Auth::user()->role === 'admin')
        <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter l'annonce</h3>
                    <form method="POST" action="{{ route('properties.reject', $property) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="admin_comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Motif du rejet *
                            </label>
                            <textarea id="admin_comment" name="admin_comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" required placeholder="Expliquez pourquoi cette annonce est rejetée..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRejectModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Rejeter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de prise de rendez-vous -->
    @if($property->status === 'validee' && $property->user_id !== Auth::id())
        <div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Prendre rendez-vous pour une visite</h3>
                    <form method="POST" action="{{ route('properties.appointment.store', $property) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet *
                            </label>
                            <input type="text" id="client_name" name="client_name" 
                                   value="{{ Auth::user()->name }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" id="client_email" name="client_email" 
                                   value="{{ Auth::user()->email }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" id="client_phone" name="client_phone" 
                                   value="{{ Auth::user()->phone ?? '' }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" 
                                   placeholder="06 12 34 56 78">
                        </div>
                        
                        <div class="mb-4">
                            <label for="requested_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et heure souhaitées *
                            </label>
                            <input type="datetime-local" id="requested_date" name="requested_date" 
                                   min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message (optionnel)
                            </label>
                            <textarea id="message" name="message" rows="3" 
                                      class="w-full border-gray-300 rounded-md shadow-sm" 
                                      placeholder="Questions particulières, informations complémentaires..."></textarea>
                        </div>
                        
                        <div class="bg-blue-50 p-3 rounded-lg mb-4">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">ℹ️ Information :</span>
                                Votre demande sera transmise au propriétaire et à notre équipe. 
                                Vous recevrez une confirmation par email une fois le rendez-vous validé.
                            </p>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAppointmentModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Envoyer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de contact propriétaire -->
    @if($property->status === 'validee' && $property->user_id !== Auth::id())
        <div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contacter le propriétaire</h3>
                    <form method="POST" action="{{ route('properties.contact.store', $property) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="contact_sender_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre nom *
                            </label>
                            <input type="text" id="contact_sender_name" name="sender_name" 
                                   value="{{ Auth::user()->name }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact_sender_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre email *
                            </label>
                            <input type="email" id="contact_sender_email" name="sender_email" 
                                   value="{{ Auth::user()->email }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact_sender_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre téléphone
                            </label>
                            <input type="tel" id="contact_sender_phone" name="sender_phone" 
                                   value="{{ Auth::user()->phone ?? '' }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" 
                                   placeholder="06 12 34 56 78">
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Sujet *
                            </label>
                            <select id="contact_subject" name="subject" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Choisir un sujet</option>
                                <option value="Demande d'informations générales">Informations générales</option>
                                <option value="Questions sur le bien">Questions sur le bien</option>
                                <option value="Demande de visite">Demande de visite</option>
                                <option value="Conditions de location/vente">Conditions de location/vente</option>
                                <option value="Disponibilité">Disponibilité</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre message *
                            </label>
                            <textarea id="contact_message" name="message" rows="4" 
                                      class="w-full border-gray-300 rounded-md shadow-sm" required
                                      placeholder="Bonjour, je suis intéressé(e) par votre annonce..."></textarea>
                        </div>
                        
                        <div class="bg-green-50 p-3 rounded-lg mb-4">
                            <p class="text-sm text-green-800">
                                <span class="font-medium">📧 Information :</span>
                                Votre message sera transmis directement au propriétaire. 
                                Vous recevrez une copie par email et pourrez suivre sa réponse dans votre dashboard.
                            </p>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeContactModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function changeMainImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
        }

        @if($property->status === 'validee' && $property->user_id !== Auth::id())
        function openAppointmentModal() {
            document.getElementById('appointmentModal').classList.remove('hidden');
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        function openContactModal() {
            document.getElementById('contactModal').classList.remove('hidden');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }
        @endif

        @if(Auth::user()->role === 'admin')
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
        @endif
    </script>
</x-app-layout> 