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
}
