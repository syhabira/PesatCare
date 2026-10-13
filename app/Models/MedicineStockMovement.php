<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStockMovement extends Model
{
    const UPDATED_AT = null;

    protected $guarded = [];

    public function batch() { return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function visit() { return $this->belongsTo(Visit::class); }
}