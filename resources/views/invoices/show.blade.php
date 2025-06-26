<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Facture #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Actions -->
            <div class="mb-6 flex justify-end space-x-2">
                @if(Auth::user()->role === 'admin')
                    @if($invoice->status === 'draft')
                        <a href="{{ route('invoices.edit', $invoice) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">Modifier</a>
                        <form method="POST" action="{{ route('invoices.send', $invoice) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">Envoyer au client</button>
                        </form>
                    @elseif($invoice->status === 'sent' || $invoice->status === 'viewed')
                        <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded text-sm">Marquer comme payée</button>
                        </form>
                    @endif
                @endif
                <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">Télécharger PDF</a>
            </div>

            <!-- Détails facture -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Facture #{{ $invoice->invoice_number }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">Émise le {{ $invoice->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($invoice->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                @elseif($invoice->status === 'viewed') bg-yellow-100 text-yellow-800
                                @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Échéance : {{ $invoice->due_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Informations client et service -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Facturer à :</h3>
                            <div class="text-gray-700 dark:text-gray-300">
                                <p class="font-medium">{{ $invoice->client->name }}</p>
                                <p>{{ $invoice->client->email }}</p>
                                <p>{{ $invoice->serviceRequest->address }}</p>
                                <p>{{ $invoice->serviceRequest->city }} {{ $invoice->serviceRequest->postal_code }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Service effectué :</h3>
                            <div class="text-gray-700 dark:text-gray-300">
                                <p class="font-medium">{{ $invoice->serviceRequest->service->name }}</p>
                                <p>Technicien : {{ $invoice->serviceRequest->technicien->name }}</p>
                                <p>Demande #{{ $invoice->serviceRequest->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails facturation -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Détails de la facturation</h3>
                    
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="py-2 text-sm text-gray-900 dark:text-gray-100">Service {{ $invoice->serviceRequest->service->name }}</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-gray-100 text-right">{{ number_format($invoice->base_amount, 0, ',', ' ') }} DZD</td>
                            </tr>
                            @if($invoice->additional_amount > 0)
                                <tr>
                                    <td class="py-2 text-sm text-gray-900 dark:text-gray-100">Frais supplémentaires</td>
                                    <td class="py-2 text-sm text-gray-900 dark:text-gray-100 text-right">{{ number_format($invoice->additional_amount, 0, ',', ' ') }} DZD</td>
                                </tr>
                            @endif
                            @if($invoice->discount_amount > 0)
                                <tr>
                                    <td class="py-2 text-sm text-gray-900 dark:text-gray-100">Remise</td>
                                    <td class="py-2 text-sm text-red-600 text-right">-{{ number_format($invoice->discount_amount, 0, ',', ' ') }} DZD</td>
                                </tr>
                            @endif
                            <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                <td class="py-4 text-lg font-bold text-gray-900 dark:text-gray-100">Total à payer</td>
                                <td class="py-4 text-lg font-bold text-gray-900 dark:text-gray-100 text-right">{{ number_format($invoice->total_amount, 0, ',', ' ') }} DZD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('service-requests.show', $invoice->serviceRequest) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Voir la demande</a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('invoices.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour aux factures</a>
                @else
                    <a href="{{ route('invoices.client') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mes factures</a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
