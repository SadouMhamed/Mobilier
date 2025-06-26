<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'appointment_type' => 'required|in:existing_request,direct_service',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:30|max:480', // 30 minutes à 8 heures
            'notes' => 'nullable|string|max:500',
        ];

        // Règles conditionnelles selon le type de rendez-vous
        if ($this->appointment_type === 'existing_request') {
            $rules['service_request_id'] = 'required|exists:service_requests,id';
        } elseif ($this->appointment_type === 'direct_service') {
            $rules['service_id'] = 'required|exists:services,id';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:100';
            $rules['description'] = 'required|string|max:1000';
            
            // Pour les admins, permettre de sélectionner un client
            if (Auth::user()->role === 'admin') {
                $rules['client_id'] = 'nullable|exists:users,id';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'appointment_type.required' => 'Veuillez sélectionner un type de rendez-vous.',
            'appointment_type.in' => 'Type de rendez-vous invalide.',
            'service_request_id.required' => 'Veuillez sélectionner une demande de service.',
            'service_request_id.exists' => 'La demande de service sélectionnée n\'existe pas.',
            'service_id.required' => 'Veuillez sélectionner un service.',
            'service_id.exists' => 'Le service sélectionné n\'existe pas.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'address.required' => 'L\'adresse d\'intervention est obligatoire.',
            'address.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
            'city.required' => 'La ville est obligatoire.',
            'city.max' => 'La ville ne peut pas dépasser 100 caractères.',
            'description.required' => 'La description du problème est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'scheduled_at.required' => 'La date et l\'heure sont obligatoires.',
            'scheduled_at.date' => 'Veuillez entrer une date et heure valides.',
            'scheduled_at.after' => 'Le rendez-vous doit être planifié dans le futur.',
            'duration.required' => 'La durée est obligatoire.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.min' => 'La durée minimum est de 30 minutes.',
            'duration.max' => 'La durée maximum est de 8 heures (480 minutes).',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
        ];
    }
}
