<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use Auditable;

    protected $fillable = [
        'student_id', 'class_id', 'recorded_by', 'updated_by', 'screened_at', 'age_months', 'height_cm',
        'weight_kg', 'bmi', 'weight_indicator_code', 'weight_indicator', 'waist_cm', 'waist_category',
        'systolic', 'diastolic', 'blood_pressure_category',
    ];
    protected $casts = ['screened_at' => 'date'];

    public function student() { return $this->belongsTo(Student::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }

    public function getBloodPressureAttribute(): ?string
    {
        return $this->systolic ? "{$this->systolic}/{$this->diastolic}" : null;
    }
}