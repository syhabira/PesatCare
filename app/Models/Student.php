<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use Auditable;

    protected $fillable = ['user_id', 'nis', 'name', 'class_id', 'gender', 'birth_date', 'is_active'];
    protected $casts = ['birth_date' => 'date', 'is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function visits() { return $this->hasMany(Visit::class); }
    public function screenings() { return $this->hasMany(Screening::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}