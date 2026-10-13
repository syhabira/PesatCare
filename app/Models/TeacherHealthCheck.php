<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class TeacherHealthCheck extends Model
{
    use Auditable;

    protected $fillable = ['teacher_id', 'recorded_by', 'updated_by', 'checked_at', 'uric_acid', 'cholesterol', 'notes'];
    protected $casts = ['checked_at' => 'date'];

    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}