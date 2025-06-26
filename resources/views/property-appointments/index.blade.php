@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Mes rendez-vous de visite
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($appointments as $appointment)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ $appointment->property->title }}
                                </h3>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                                    {{ $appointment->status_label }}
                                </span>

                                <div class="mt-2 text-sm text-gray-600">
                                    <p><strong>Date demandée:</strong> {{ $appointment->requested_date->format('d/m/Y à H:i') }}</p>
                                    @if($appointment->confirmed_date)
                                    <p><strong>Date confirmée:</strong> {{ $appointment->confirmed_date->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                                    <div class="text-lg font-bold text-blue-600">
                        {{ number_format($appointment->property->price, 0, ',', ' ') }} DZD
                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Aucun rendez-vous de visite programmé.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 