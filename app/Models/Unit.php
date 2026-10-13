<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use Auditable;

    protected $fillable = ['unit_name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function teachers() { return $this->hasMany(Teacher::class); }
}