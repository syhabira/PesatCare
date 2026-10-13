<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    use Auditable;

    protected $fillable = ['visit_id', 'letter_number', 'status_home', 'departure_time', 'additional_notes', 'created_by'];
    protected $casts = ['status_home' => 'boolean', 'departure_time' => 'datetime'];

    public function visit() { return $this->belongsTo(Visit::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}