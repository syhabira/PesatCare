@extends('layouts.app')
@section('content')
<h2>{{ $medicine->name }}</h2>
<p>Golongan: {{ $medicine->medicine_class ?? '-' }} | Indikasi: {{ $medicine->indication ?? '-' }}<br>
Stok tersedia (belum kedaluwarsa): <b>{{ (int) $medicine->stock }} {{ $medicine->unit }}</b> | Total pernah digunakan: {{ $usedTotal }} {{ $medicine->unit }}</p>

<h3>Tambah stok</h3>
<form method="POST" action="{{ route('admin.medicines.stock.add', $medicine) }}" class="card row">
    @csrf
    <label>No. batch<input name="batch_number" required></label>
    <label>Jumlah<input type="number" name="quantity" min="1" required style="width:90px"></label>
    <label>Kedaluwarsa<input type="date" name="expiry_date" required></label>
    <label>Catatan<input name="notes"></label>
    <button class="btn">Tambah</button>
</form>

<h3>Batch</h3>
<table>
    <tr><th>Batch</th><th>Jumlah</th><th>Kedaluwarsa</th><th>Status</th><th>Koreksi stok</th></tr>
    @forelse($batches as $b)
    <tr>
        <td>{{ $b->batch_number }}</td><td>{{ $b->quantity }}</td><td>{{ $b->expiry_date->format('d/m/Y') }}</td>
        <td>@if($b->isExpired())<b style="color:#b91c1c">Kedaluwarsa</b>@elseif($b->isNearExpiry())<b style="color:#b45309">Mendekati kedaluwarsa</b>@else OK @endif</td>
        <td>
            <form method="POST" action="{{ route('admin.batches.adjust', $b) }}" onsubmit="return confirm('Koreksi stok batch ini?')">
                @csrf @method('PATCH')
                <input type="number" name="quantity" min="0" value="{{ $b->quantity }}" style="width:80px" required>
                <input name="notes" placeholder="Alasan (wajib)" required>
                <button class="btn">Koreksi</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada batch.</td></tr>
    @endforelse
</table>

<h3>Riwayat mutasi stok</h3>
<table>
    <tr><th>Waktu</th><th>Batch</th><th>Jenis</th><th>Perubahan</th><th>Saldo</th><th>Oleh</th><th>Kunjungan</th><th>Catatan</th></tr>
    @forelse($movements as $m)
    <tr>
        <td>{{ $m->created_at->format('d/m/Y H:i') }}</td><td>{{ $m->batch->batch_number }}</td>
        <td>{{ ['initial' => 'Stok awal', 'in' => 'Masuk', 'out' => 'Keluar', 'return' => 'Dikembalikan', 'adjustment' => 'Koreksi'][$m->type] }}</td>
        <td>{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td><td>{{ $m->balance_after }}</td>
        <td>{{ $m->user->name }}</td>
        <td>@if($m->visit)<a href="{{ route('admin.visits.show', $m->visit) }}">#{{ $m->visit->id }}</a>@endif</td>
        <td>{{ $m->notes }}</td>
    </tr>
    @empty
    <tr><td colspan="8" class="muted">Belum ada mutasi.</td></tr>
    @endforelse
</table>
{{ $movements->links() }}
<a href="{{ route('admin.medicines.index') }}">&larr; Kembali</a>
@endsection