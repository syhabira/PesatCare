<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use Auditable;

    protected $fillable = ['name', 'indication', 'medicine_class', 'symbol', 'unit', 'low_stock_threshold', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function batches() { return $this->hasMany(MedicineBatch::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }

    /** Menambahkan atribut `stock` = total stok batch yang belum kedaluwarsa. */
    public function scopeWithStock($q)
    {
        return $q->withSum(['batches as stock' => fn ($b) => $b->whereDate('expiry_date', '>=', today())], 'quantity');
    }

    public function isLowStock(): bool
    {
        return (int) ($this->stock ?? 0) <= $this->low_stock_threshold;
    }
}