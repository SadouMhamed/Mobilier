<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_request_id',
        'scheduled_at',
        'duration',
        'notes',
        'status',
        'completed_at',
        'cancellation_reason',
        'proposed_by',
        'proposed_date',
        'proposed_reason',
        'is_locked',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'proposed_date' => 'datetime',
        'is_locked' => 'boolean',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    // Scopes
    public function scopeRequested($query)
    {
        return $query->where('status', 'demandee');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'planifiee');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmee');
    }
}
