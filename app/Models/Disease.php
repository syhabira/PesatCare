<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use Auditable;

    protected $fillable = ['name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function visits() { return $this->belongsToMany(Visit::class, 'visit_diseases'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}