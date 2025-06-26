<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'technicien_id',
        'description',
        'priority',
        'preferred_date',
        'address',
        'city',
        'postal_code',
        'phone',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'completion_notes',
        'admin_notes',
        'technicien_notes',
        'is_archived',
        'archived_at',
        'final_notes',
        'client_rating',
        'client_feedback',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'datetime',
            'assigned_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'archived_at' => 'datetime',
            'is_archived' => 'boolean',
        ];
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function taskReports()
    {
        return $this->hasMany(TaskReport::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assignee');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'en_cours');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'terminee');
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }
}
