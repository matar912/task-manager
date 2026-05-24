<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'status', 'priority',
        'due_date', 'user_id', 'category_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // Valeurs possibles pour status et priority
    const STATUSES   = ['todo', 'in_progress', 'done', 'archived'];
    const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Scope utile : tâches non terminées
    public function scopePending($query)
    {
        return $query->whereNotIn('status', ['done', 'archived']);
    }

    // Scope : tâches dues dans les 24h
    public function scopeDueSoon($query)
    {
        return $query->whereBetween('due_date', [now(), now()->addDay()]);
    }
}
