<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'sender_id',
        'recipient_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'subject',
        'message',
        'is_read',
        'read_at',
        'reply',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    // Relations
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('reply');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('reply');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function reply($replyText)
    {
        $this->update([
            'reply' => $replyText,
            'replied_at' => now(),
            'is_read' => true,
            'read_at' => $this->read_at ?? now(),
        ]);
    }
}
