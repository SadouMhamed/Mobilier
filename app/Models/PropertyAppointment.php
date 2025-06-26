<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'client_id',
        'client_name',
        'client_email',
        'client_phone',
        'requested_date',
        'confirmed_date',
        'message',
        'status',
        'admin_notes',
        'completion_notes',
        'cancellation_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'datetime',
            'confirmed_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relations
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'completed' => 'Terminé',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
