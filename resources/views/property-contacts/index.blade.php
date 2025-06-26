@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(auth()->user()->role === 'admin')
                Tous les messages d'annonces
            @elseif(auth()->user()->role === 'client')
                Mes messages envoyés
            @else
                Messages reçus pour mes annonces
            @endif
        </h2>
        <div class="flex space-x-2">
            @if(auth()->user()->role !== 'client')
                <a href="{{ route('property-contacts.received') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Messages reçus
                </a>
            @endif
            <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                Retour aux annonces
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $contacts->count() }}
                            </p>
                            <p class="text-gray-600">Total</p>
                        </div>
                        <div class="text-3xl text-blue-600">📧</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $contacts->where('is_read', false)->count() }}
                            </p>
                            <p class="text-gray-600">Non lus</p>
                        </div>
                        <div class="text-3xl text-yellow-600">📩</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $contacts->whereNotNull('reply')->count() }}
                            </p>
                            <p class="text-gray-600">Avec réponse</p>
                        </div>
                        <div class="text-3xl text-green-600">💬</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-red-600">
                                {{ $contacts->whereNull('reply')->count() }}
                            </p>
                            <p class="text-gray-600">Sans réponse</p>
                        </div>
                        <div class="text-3xl text-red-600">❓</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des contacts -->
        <div class="space-y-4">
            @forelse($contacts as $contact)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ !$contact->is_read ? 'border-l-4 border-blue-500' : '' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $contact->subject }}
                                    </h3>
                                    
                                    @if(!$contact->is_read)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Nouveau
                                        </span>
                                    @endif

                                    @if($contact->reply)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Répondu
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                    <div>
                                        <strong>Annonce:</strong> {{ $contact->property->title }}
                                    </div>
                                    <div>
                                        <strong>{{ auth()->user()->role === 'client' ? 'Destinataire' : 'Expéditeur' }}:</strong> 
                                        {{ auth()->user()->role === 'client' ? $contact->recipient->name : $contact->sender_name }}
                                    </div>
                                    <div>
                                        <strong>Date:</strong> {{ $contact->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-3 rounded mb-3">
                                    <p class="text-gray-700 text-sm">{{ Str::limit($contact->message, 150) }}</p>
                                </div>

                                @if($contact->reply)
                                <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                    <h4 class="font-medium text-green-800 text-sm mb-1">💬 Réponse</h4>
                                    <p class="text-green-700 text-sm">{{ Str::limit($contact->reply, 150) }}</p>
                                    <p class="text-xs text-green-600 mt-1">
                                        Répondu {{ $contact->replied_at->diffForHumans() }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            <div class="text-right ml-4">
                                                                    <div class="text-lg font-bold text-blue-600">
                                        {{ number_format($contact->property->price, 0, ',', ' ') }} DZD
                                    </div>
                                <div class="text-sm text-gray-500">
                                    {{ $contact->property->city }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex space-x-2">
                                <a href="{{ route('property-contacts.show', $contact) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>
                                <a href="{{ route('properties.show', $contact->property) }}" 
                                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    Voir l'annonce
                                </a>
                                
                                @if(!$contact->is_read && ($contact->recipient_id === auth()->id() || auth()->user()->role === 'admin'))
                                <a href="{{ route('property-contacts.mark-read', $contact) }}" 
                                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Marquer lu
                                </a>
                                @endif
                            </div>

                            <div class="text-sm text-gray-500">
                                #{{ $contact->id }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun message</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(auth()->user()->role === 'client')
                                Vous n'avez pas encore envoyé de message.
                            @else
                                Aucun message reçu pour vos annonces.
                            @endif
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('properties.public') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Parcourir les annonces
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($contacts->hasPages())
            <div class="mt-8">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 