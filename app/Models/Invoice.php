<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'service_request_id',
        'client_id',
        'created_by',
        'base_amount',
        'additional_amount',
        'discount_amount',
        'total_amount',
        'admin_notes',
        'status',
        'sent_at',
        'viewed_at',
        'paid_at',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'viewed_at' => 'datetime',
            'paid_at' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    // Relations
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Méthodes utiles
    public function markAsViewed()
    {
        if (!$this->viewed_at) {
            $this->update(['viewed_at' => now(), 'status' => 'viewed']);
        }
    }

    public function markAsSent()
    {
        $this->update(['sent_at' => now(), 'status' => 'sent']);
    }

    public function markAsPaid()
    {
        $this->update(['paid_at' => now(), 'status' => 'paid']);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'sent' => 'Envoyée',
            'viewed' => 'Vue',
            'paid' => 'Payée',
            'cancelled' => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'sent' => 'bg-blue-100 text-blue-800',
            'viewed' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Génération automatique du numéro de facture
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -3) + 1 : 1;
        
        return 'FAC-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
