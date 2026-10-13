<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'student_id', 'teacher_id', 'class_id', 'unit_id', 'recorded_by', 'updated_by', 'visited_at',
        'temperature', 'systolic', 'diastolic', 'pulse', 'complaint', 'anamnesis', 'action', 'notes',
        'triage_level', 'triage_reason', 'triage_snapshot', 'triage_deadline', 'triage_status',
        'triage_handled_at', 'left_at', 'follow_up_status',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'triage_deadline' => 'datetime',
        'triage_handled_at' => 'datetime',
        'left_at' => 'datetime',
        'triage_snapshot' => 'array',
        'temperature' => 'decimal:1',
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function diseases() { return $this->belongsToMany(Disease::class, 'visit_diseases'); }
    public function medicineUsages() { return $this->hasMany(VisitMedicine::class); }
    public function certificate() { return $this->hasOne(MedicalCertificate::class); }

    public function getPatientNameAttribute(): string
    {
        return $this->student?->name ?? $this->teacher?->name ?? '-';
    }

    public function getBloodPressureAttribute(): ?string
    {
        return $this->systolic ? "{$this->systolic}/{$this->diastolic}" : null;
    }

    public function isTriageOverdue(): bool
    {
        return $this->triage_status === 'pending' && $this->triage_deadline?->isPast();
    }

    public function scopeOverdue($q)
    {
        return $q->where('triage_status', 'pending')->where('triage_deadline', '<', now());
    }
}