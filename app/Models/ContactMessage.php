<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'read_at',
        'replied_at',
        'admin_notes',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Check if the message has been read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if the message has been replied to.
     */
    public function isReplied(): bool
    {
        return !is_null($this->replied_at);
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark the message as replied.
     */
    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
        ]);
    }

    /**
     * Scope to get unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope to get read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope to get replied messages.
     */
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    /**
     * Scope to get messages by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent messages.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
