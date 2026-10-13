@extends('layouts.app')
@section('content')
<h2>Halo, {{ $teacher->name }}</h2>
<div class="row">
    <div class="card">NIP<br><b>{{ $teacher->nip }}</b></div>
    <div class="card">Unit<br><b>{{ $teacher->unit?->unit_name ?? '-' }}</b></div>
    <div class="card">Total kunjungan UKS<br><b>{{ $visitCount }}</b></div>
</div>

<h3>Pemeriksaan terakhir</h3>
@if($lastCheck)
    <div class="card">{{ $lastCheck->checked_at->format('d/m/Y') }} - Asam urat: {{ $lastCheck->uric_acid ?? '-' }}, Kolesterol: {{ $lastCheck->cholesterol ?? '-' }}, Gol. darah: {{ $teacher->blood_type ?? '-' }}</div>
@else
    <p class="muted">Belum ada pemeriksaan.</p>
@endif

<h3>Riwayat singkat</h3>
<table>
    <tr><th>Tanggal</th><th>Asam urat</th><th>Kolesterol</th></tr>
    @forelse($checks as $c)<tr><td>{{ $c->checked_at->format('d/m/Y') }}</td><td>{{ $c->uric_acid ?? '-' }}</td><td>{{ $c->cholesterol ?? '-' }}</td></tr>
    @empty<tr><td colspan="3" class="muted">Belum ada data.</td></tr>@endforelse
</table>
<a href="{{ route('guru.health-checks') }}">Lihat semua</a>
@endsection