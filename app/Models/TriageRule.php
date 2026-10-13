<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class TriageRule extends Model
{
    use Auditable;

    protected $fillable = [
        'name', 'parameter', 'operator', 'value', 'value_max', 'disease_id', 'level',
        'message', 'deadline_minutes', 'is_active', 'is_validated', 'validated_by', 'validated_at', 'version',
    ];
    protected $casts = ['is_active' => 'boolean', 'is_validated' => 'boolean', 'validated_at' => 'datetime'];

    public function disease() { return $this->belongsTo(Disease::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}