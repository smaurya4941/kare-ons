<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * A single audit-trail entry. Records are normally written automatically by the
 * {@see \App\Support\LogsActivity} trait, but `record()` can also be called
 * directly for bespoke events (e.g. bulk actions, settings changes).
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'causer_id', 'causer_name', 'event', 'log_name', 'description',
        'subject_type', 'subject_id', 'properties', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /* ---------------------------------------------------------------------
     | Relations
     * ------------------------------------------------------------------- */

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /* ---------------------------------------------------------------------
     | Scopes
     * ------------------------------------------------------------------- */

    public function scopeEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    public function scopeForSubjectType(Builder $query, string $type): Builder
    {
        return $query->where('subject_type', $type);
    }

    /* ---------------------------------------------------------------------
     | Writer
     * ------------------------------------------------------------------- */

    /**
     * Persist an activity entry. Automatically captures the current admin,
     * request IP and user-agent. Never throws — auditing must not break the
     * action being audited.
     */
    public static function record(
        string $event,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        string $logName = 'default'
    ): ?self {
        try {
            $user = Auth::user();

            return static::create([
                'causer_id'    => $user?->id,
                'causer_name'  => $user?->name ?? 'System',
                'event'        => $event,
                'log_name'     => $logName,
                'description'  => $description,
                'subject_type' => $subject ? $subject::class : null,
                'subject_id'   => $subject?->getKey(),
                'properties'   => $properties ?: null,
                'ip_address'   => request()?->ip(),
                'user_agent'   => substr((string) request()?->userAgent(), 0, 255) ?: null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to write activity log', [
                'error'       => $e->getMessage(),
                'event'       => $event,
                'description' => $description,
            ]);

            return null;
        }
    }

    /* ---------------------------------------------------------------------
     | Presentation helpers
     * ------------------------------------------------------------------- */

    public function getIconAttribute(): string
    {
        return match ($this->event) {
            'created' => 'add_circle',
            'updated' => 'edit',
            'deleted' => 'delete',
            'restored' => 'restore',
            default   => 'history',
        };
    }

    public function getColorAttribute(): string
    {
        return match ($this->event) {
            'created' => 'emerald',
            'updated' => 'indigo',
            'deleted' => 'red',
            'restored' => 'amber',
            default   => 'gray',
        };
    }

    /**
     * Human-friendly model name of the subject, e.g. "Product".
     */
    public function getSubjectLabelAttribute(): ?string
    {
        return $this->subject_type ? class_basename($this->subject_type) : null;
    }
}
