<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn ($m) => AuditLog::record('created', $m, null, $m->getAttributes()));
        static::updated(function ($m) {
            $changes = $m->getChanges();
            AuditLog::record('updated', $m, array_intersect_key($m->getOriginal(), $changes), $changes);
        });
        static::deleted(fn ($m) => AuditLog::record('deleted', $m, $m->getOriginal(), null));
    }
}