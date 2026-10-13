<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use Auditable;

    protected $fillable = ['user_id', 'nip', 'name', 'unit_id', 'lp_number', 'blood_type', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function visits() { return $this->hasMany(Visit::class); }
    public function healthChecks() { return $this->hasMany(TeacherHealthCheck::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}