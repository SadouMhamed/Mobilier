<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'speciality',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'client_id');
    }

    public function assignedServiceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'technicien_id');
    }

    public function propertyAppointments()
    {
        return $this->hasMany(PropertyAppointment::class, 'client_id');
    }

    public function sentPropertyContacts()
    {
        return $this->hasMany(PropertyContact::class, 'sender_id');
    }

    public function receivedPropertyContacts()
    {
        return $this->hasMany(PropertyContact::class, 'recipient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    // Helper methods pour les rôles
    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isTechnicien()
    {
        return $this->role === 'technicien';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
