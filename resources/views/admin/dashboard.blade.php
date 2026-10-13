@extends('layouts.app')
@section('content')
<h2>Dashboard</h2>
@include('partials.period-filter')

<div class="row">
    <div class="card">Kunjungan hari ini<br><b>{{ $stats['visits_today'] }}</b></div>
    <div class="card">Kunjungan bulan ini<br><b>{{ $stats['visits_month'] }}</b></div>
    <div class="card">Kunjungan periode ini<br><b>{{ $stats['visits_period'] }}</b></div>
    <div class="card">Total siswa aktif<br><b>{{ $stats['total_students'] }}</b></div>
    <div class="card">Total obat aktif<br><b>{{ $stats['total_medicines'] }}</b></div>
    <div class="card">Jumlah screening<br><b>{{ $stats['screening_count'] }}</b></div>
    <div class="card">Siswa sudah screening<br><b>{{ $stats['screened_students'] }}</b></div>
    <div class="card">Triase terlambat<br><b>{{ $stats['overdue_triage'] }}</b></div>
</div>

@if($stats['overdue_visits']->count())
<h3>Triase melewati batas waktu</h3>
<table>
    <tr><th>Pasien</th><th>Triase</th><th>Batas waktu</th><th></th></tr>
    @foreach($stats['overdue_visits'] as $v)
    <tr>
        <td>{{ $v->patient_name }}</td>
        <td><x-triage :level="$v->triage_level" /></td>
        <td>{{ $v->triage_deadline->format('d/m/Y H:i') }}</td>
        <td><a href="{{ route('admin.visits.show', $v) }}">Buka</a></td>
    </tr>
    @endforeach
</table>
@endif

<h3>Kunjungan per hari</h3>
<table>
    <tr><th>Tanggal</th><th>Jumlah</th></tr>
    @forelse($stats['chart'] as $c)<tr><td>{{ $c->day }}</td><td>{{ $c->total }}</td></tr>
    @empty<tr><td colspan="2" class="muted">Belum ada data.</td></tr>@endforelse
</table>

<div class="row" style="align-items:flex-start">
    <div style="flex:1;min-width:280px"><h3>Penyakit terbanyak</h3>
        <table>@forelse($stats['top_diseases'] as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
    <div style="flex:1;min-width:280px"><h3>Kelas terbanyak</h3>
        <table>@forelse($stats['top_classes'] as $r)<tr><td>{{ $r->class_name }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
</div>
<div class="row" style="align-items:flex-start">
    <div style="flex:1;min-width:280px"><h3>Siswa terbanyak berkunjung</h3>
        <table>@forelse($stats['top_students'] as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->class_name }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
    <div style="flex:1;min-width:280px"><h3>Obat terbanyak digunakan</h3>
        <table>@forelse($stats['top_medicines'] as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->total }} {{ $r->unit }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
</div>

<div class="row" style="align-items:flex-start">
    <div style="flex:1;min-width:280px"><h3>Stok menipis</h3>
        <table>@forelse($stats['low_stock'] as $m)<tr><td>{{ $m->name }}</td><td>{{ (int) $m->stock }} {{ $m->unit }} (batas {{ $m->low_stock_threshold }})</td></tr>@empty<tr><td class="muted">Tidak ada.</td></tr>@endforelse</table>
    </div>
    <div style="flex:1;min-width:280px"><h3>Obat kedaluwarsa / mendekati</h3>
        <table>@forelse($stats['expiring'] as $b)<tr><td>{{ $b->medicine->name }} ({{ $b->batch_number }})</td><td>{{ $b->quantity }} {{ $b->medicine->unit }}</td><td>{{ $b->expiry_date->format('d/m/Y') }} {{ $b->isExpired() ? '(kedaluwarsa)' : '' }}</td></tr>@empty<tr><td class="muted">Tidak ada.</td></tr>@endforelse</table>
    </div>
</div>

<div class="row" style="align-items:flex-start">
    <div style="flex:1;min-width:280px"><h3>Indikator berat badan</h3>
        <table>@forelse($stats['weight_indicators'] as $r)<tr><td>{{ $r->label }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
    <div style="flex:1;min-width:280px"><h3>Kategori tekanan darah</h3>
        <table>@forelse($stats['bp_categories'] as $r)<tr><td>{{ $r->label }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
</div>
@endsection