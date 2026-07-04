<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Drop-in audit logging for Eloquent models.
 *
 * Add `use LogsActivity;` to a model to automatically record create / update /
 * delete events to the {@see ActivityLog}. By default only actions performed by
 * an authenticated admin are logged, so customer-side writes (e.g. stock
 * decremented at checkout) don't pollute the admin audit trail.
 *
 * Models may customise behaviour with these optional properties/methods:
 *   protected array $activityLogEvents = ['created', 'updated', 'deleted'];
 *   protected array $activityLogIgnore = ['updated_at'];
 *   protected bool  $activityLogGuests = false;   // also log non-admin actions
 *   public function activityLabel(): string { ... } // friendly subject name
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            $model->recordActivity('created');
        });

        static::updated(function (Model $model) {
            $model->recordActivity('updated');
        });

        static::deleted(function (Model $model) {
            $model->recordActivity('deleted');
        });
    }

    protected function loggableEvents(): array
    {
        return property_exists($this, 'activityLogEvents')
            ? $this->activityLogEvents
            : ['created', 'updated', 'deleted'];
    }

    protected function ignoredAttributes(): array
    {
        $custom = property_exists($this, 'activityLogIgnore') ? $this->activityLogIgnore : [];

        return array_merge(['updated_at', 'created_at', 'remember_token', 'password'], $custom);
    }

    protected function shouldLogGuestActivity(): bool
    {
        return property_exists($this, 'activityLogGuests') ? $this->activityLogGuests : false;
    }

    /**
     * A readable name for this record used in the log description.
     */
    public function activityLabel(): string
    {
        foreach (['name', 'title', 'order_number', 'code', 'email'] as $attr) {
            if (! empty($this->{$attr})) {
                return (string) $this->{$attr};
            }
        }

        return '#' . $this->getKey();
    }

    public function recordActivity(string $event): void
    {
        if (! in_array($event, $this->loggableEvents(), true)) {
            return;
        }

        // Only audit admin actions unless the model opts into guest logging.
        $user = Auth::user();
        $isAdmin = $user && ($user->role ?? null) === 'admin';
        if (! $isAdmin && ! $this->shouldLogGuestActivity()) {
            return;
        }

        $modelName = strtolower(class_basename(static::class));
        $label     = $this->activityLabel();

        $verb = match ($event) {
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            default   => $event,
        };

        $description = ucfirst($verb) . " {$modelName} \"{$label}\"";

        ActivityLog::record(
            event: $event,
            description: $description,
            subject: $this,
            properties: $this->activityLogProperties($event),
        );
    }

    /**
     * Build the properties payload: for updates capture the before/after of the
     * changed (non-ignored) attributes; for creates/deletes snapshot the key
     * identifying fields.
     */
    protected function activityLogProperties(string $event): array
    {
        $ignore = $this->ignoredAttributes();

        if ($event === 'updated') {
            $changes = array_diff_key($this->getChanges(), array_flip($ignore));

            if (empty($changes)) {
                return [];
            }

            $original = $this->getOriginal();
            $old = [];
            foreach ($changes as $key => $new) {
                $old[$key] = $original[$key] ?? null;
            }

            return [
                'old'        => $old,
                'attributes' => $changes,
            ];
        }

        // created / deleted: snapshot a trimmed set of attributes.
        return [
            'attributes' => array_diff_key($this->getAttributes(), array_flip($ignore)),
        ];
    }
}
