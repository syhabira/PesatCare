<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $guarded = [];
    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    public static function record(string $action, Model $m, ?array $old, ?array $new): void
    {
        $strip = fn (?array $a) => $a === null ? null
            : array_diff_key($a, array_flip(['password', 'remember_token', 'created_at', 'updated_at']));

        static::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => $action,
            'entity_type' => class_basename($m),
            'entity_id' => $m->getKey(),
            'old_values' => $strip($old),
            'new_values' => $strip($new),
            'ip_address' => request()?->ip(),
        ]);
    }

    public function user() { return $this->belongsTo(User::class); }
}