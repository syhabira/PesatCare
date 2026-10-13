<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitMedicine extends Model
{
    protected $fillable = ['visit_id', 'medicine_batch_id', 'quantity', 'notes'];

    public function visit() { return $this->belongsTo(Visit::class); }
    public function batch() { return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id'); }
}