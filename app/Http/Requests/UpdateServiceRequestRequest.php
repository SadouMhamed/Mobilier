<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateServiceRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $serviceRequest = $this->route('service_request');
        return Auth::check() && 
               (Auth::user()->role === 'admin' || 
                (Auth::user()->role === 'client' && $serviceRequest->client_id === Auth::id()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'description' => 'required|string|min:20|max:1000',
            'priority' => 'required|in:basse,normale,haute,urgente',
            'preferred_date' => 'nullable|date|after:today',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|size:5',
            'phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Veuillez sélectionner un service.',
            'service_id.exists' => 'Le service sélectionné n\'existe pas.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 20 caractères.',
            'priority.required' => 'Veuillez indiquer la priorité.',
            'priority.in' => 'La priorité doit être : basse, normale, haute ou urgente.',
            'preferred_date.date' => 'Veuillez entrer une date valide.',
            'preferred_date.after' => 'La date préférée doit être dans le futur.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',

            'postal_code.size' => 'Le code postal doit contenir 5 chiffres.',
        ];
    }
}
