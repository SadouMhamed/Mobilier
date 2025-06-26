<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'technicien_id',
        'task_title',
        'task_description',
        'duration_minutes',
        'material_cost',
        'materials_used',
        'difficulty',
        'before_photos',
        'after_photos',
        'observations',
        'recommendations',
    ];

    protected function casts(): array
    {
        return [
            'before_photos' => 'array',
            'after_photos' => 'array',
        ];
    }

    // Relations
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    // Accesseurs
    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty) {
            'facile' => '🟢 Facile',
            'normale' => '🟡 Normale',
            'difficile' => '🟠 Difficile',
            'complexe' => '🔴 Complexe',
            default => 'Non défini'
        };
    }

    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty) {
            'facile' => 'bg-green-100 text-green-800',
            'normale' => 'bg-yellow-100 text-yellow-800',
            'difficile' => 'bg-orange-100 text-orange-800',
            'complexe' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return 'Non spécifiée';
        }

        $hours = intval($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'min';
        } elseif ($hours > 0) {
            return $hours . 'h';
        } else {
            return $minutes . 'min';
        }
    }
}
