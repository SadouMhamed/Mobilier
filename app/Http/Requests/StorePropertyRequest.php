<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role === 'client' || Auth::user()->role === 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:vente,location',
            'property_type' => 'required|in:appartement,maison,studio,bureau,terrain,local',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|size:5',
            'surface' => 'nullable|integer|min:1',
            'rooms' => 'nullable|integer|min:1',
            'bathrooms' => 'nullable|integer|min:1',
            'furnished' => 'boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
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
            'title.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 50 caractères.',
            'type.required' => 'Le type d\'annonce est obligatoire.',
            'property_type.required' => 'Le type de bien est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'postal_code.size' => 'Le code postal doit contenir 5 chiffres.',
            'images.max' => 'Vous ne pouvez télécharger que 10 images maximum.',
            'images.*.image' => 'Seules les images sont autorisées.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 2MB.',
        ];
    }
}
