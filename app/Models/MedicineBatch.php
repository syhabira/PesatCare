<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = ['medicine_id', 'batch_number', 'quantity', 'expiry_date'];
    protected $casts = ['expiry_date' => 'date'];

    public function medicine() { return $this->belongsTo(Medicine::class); }
    public function movements() { return $this->hasMany(MedicineStockMovement::class); }

    public function isExpired(): bool { return $this->expiry_date->lt(today()); }
    public function isNearExpiry(): bool
    {
        return ! $this->isExpired()
            && $this->expiry_date->lte(today()->addDays(config('uks.expiry_warning_days')));
    }
}