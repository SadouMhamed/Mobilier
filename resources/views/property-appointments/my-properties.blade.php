<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rendez-vous pour mes annonces') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Rendez-vous demandés pour vos annonces</h3>
                        <div class="text-sm text-gray-600">
                            Total: {{ $appointments->total() }} rendez-vous
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                                                    {{ $appointment->status_label }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    ID: #{{ $appointment->id }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <h4 class="font-semibold text-blue-600 mb-2">
                                                        <a href="{{ route('properties.show', $appointment->property) }}" class="hover:underline">
                                                            {{ $appointment->property->title }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Lieu:</strong> {{ $appointment->property->city }}, {{ $appointment->property->postal_code }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Prix:</strong> {{ number_format($appointment->property->price, 0, ',', ' ') }} DZD
                                                    </p>
                                                </div>
                                                
                                                <div>
                                                    <h5 class="font-medium mb-2">Demandeur</h5>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Nom:</strong> {{ $appointment->client_name }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Email:</strong> {{ $appointment->client_email }}
                                                    </p>
                                                    @if($appointment->client_phone)
                                                        <p class="text-sm text-gray-600 mb-1">
                                                            <strong>Téléphone:</strong> {{ $appointment->client_phone }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Date demandée:</strong> 
                                                        {{ $appointment->requested_date->format('d/m/Y à H:i') }}
                                                    </p>
                                                    @if($appointment->confirmed_date)
                                                        <p class="text-sm text-gray-600 mb-1">
                                                            <strong>Date confirmée:</strong> 
                                                            {{ $appointment->confirmed_date->format('d/m/Y à H:i') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <div>
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Demandé le:</strong> 
                                                        {{ $appointment->created_at->format('d/m/Y à H:i') }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if($appointment->message)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-600 mb-1"><strong>Message:</strong></p>
                                                    <p class="text-sm text-gray-700 bg-white p-2 rounded border">
                                                        {{ $appointment->message }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($appointment->admin_notes)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-600 mb-1"><strong>Notes admin:</strong></p>
                                                    <p class="text-sm text-gray-700 bg-blue-50 p-2 rounded border">
                                                        {{ $appointment->admin_notes }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($appointment->completion_notes)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-600 mb-1"><strong>Notes de fin:</strong></p>
                                                    <p class="text-sm text-gray-700 bg-green-50 p-2 rounded border">
                                                        {{ $appointment->completion_notes }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($appointment->cancellation_reason)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-600 mb-1"><strong>Raison d'annulation:</strong></p>
                                                    <p class="text-sm text-gray-700 bg-red-50 p-2 rounded border">
                                                        {{ $appointment->cancellation_reason }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4 flex flex-col space-y-2">
                                            <a href="{{ route('property-appointments.show', $appointment) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Voir détails
                                            </a>
                                            
                                            @if($appointment->status === 'pending')
                                                <p class="text-xs text-gray-500 text-center">
                                                    En attente de<br>validation admin
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 8v-2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h6m-8 0h8a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Aucun rendez-vous n'a encore été demandé pour vos annonces.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('properties.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Voir mes annonces
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 