<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'property_type',
        'price',
        'address',
        'city',
        'postal_code',
        'surface',
        'rooms',
        'bathrooms',
        'furnished',
        'images',
        'status',
        'admin_comment',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'furnished' => 'boolean',
            'images' => 'array',
            'validated_at' => 'datetime',
        ];
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function propertyAppointments()
    {
        return $this->hasMany(PropertyAppointment::class);
    }

    public function propertyContacts()
    {
        return $this->hasMany(PropertyContact::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeValidated($query)
    {
        return $query->where('status', 'validee');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'en_attente');
    }

    // Helper methods
    public function isValidated()
    {
        return $this->status === 'validee';
    }

    public function isPending()
    {
        return $this->status === 'en_attente';
    }

    public function isSold()
    {
        return $this->status === 'vendue';
    }

    public function isRented()
    {
        return $this->status === 'louee';
    }

    public function isRejected()
    {
        return $this->status === 'rejetee';
    }

    public function isAvailable()
    {
        return $this->status === 'validee';
    }

    public function isUnavailable()
    {
        return in_array($this->status, ['vendue', 'louee']);
    }

    // Accesseurs pour l'affichage
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'en_attente' => 'En attente de validation',
            'validee' => 'Validée',
            'rejetee' => 'Rejetée',
            'vendue' => 'Vendue',
            'louee' => 'Louée',
            default => 'Statut inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'validee' => 'bg-green-100 text-green-800',
            'rejetee' => 'bg-red-100 text-red-800',
            'vendue' => 'bg-purple-100 text-purple-800',
            'louee' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'vente' => 'À vendre',
            'location' => 'À louer',
            default => 'Type inconnu'
        };
    }

    public function getPropertyTypeDisplayAttribute()
    {
        return match($this->property_type) {
            'appartement' => 'Appartement',
            'maison' => 'Maison',
            'studio' => 'Studio',
            'bureau' => 'Bureau',
            'terrain' => 'Terrain',
            'local' => 'Local commercial',
            default => 'Type inconnu'
        };
    }
}
