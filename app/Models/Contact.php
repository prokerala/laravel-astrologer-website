<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_response',
        'responded_at',
        'responded_by',
        'is_read'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeResponded($query)
    {
        return $query->whereNotNull('admin_response');
    }

    public function scopeUnresponded($query)
    {
        return $query->whereNull('admin_response');
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function hasResponse(): bool
    {
        return !is_null($this->admin_response);
    }
}
