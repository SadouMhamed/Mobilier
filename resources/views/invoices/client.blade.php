<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mes factures
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Mes factures</h3>
                    
                    @if($invoices->count() > 0)
                        <div class="space-y-4">
                            @foreach($invoices as $invoice)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    Facture #{{ $invoice->invoice_number }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($invoice->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                                    @elseif($invoice->status === 'viewed') bg-yellow-100 text-yellow-800
                                                    @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if($invoice->status === 'draft') Brouillon
                                                    @elseif($invoice->status === 'sent') Envoyée
                                                    @elseif($invoice->status === 'viewed') Vue
                                                    @elseif($invoice->status === 'paid') Payée
                                                    @else Annulée @endif
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Service :</span>
                                                    <span class="text-gray-600 dark:text-gray-400">{{ $invoice->serviceRequest->service->name }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Émise le :</span>
                                                    <span class="text-gray-600 dark:text-gray-400">{{ $invoice->created_at->format('d/m/Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Échéance :</span>
                                                    <span class="text-gray-600 dark:text-gray-400">{{ $invoice->due_date->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right ml-6">
                                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                                {{ number_format($invoice->total_amount, 0, ',', ' ') }} DZD
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('invoices.show', $invoice) }}" 
                                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Voir détails
                                                </a>
                                                <a href="{{ route('invoices.pdf', $invoice) }}" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune facture</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Vous n'avez pas encore de factures.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
