<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use Auditable;

    protected $table = 'classes';
    protected $fillable = ['class_name', 'grade_level', 'school_level', 'academic_year', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function students() { return $this->hasMany(Student::class, 'class_id'); }
}