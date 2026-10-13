@extends('layouts.app')
@section('content')
<h2>Rekap & Statistik</h2>
@include('partials.period-filter')

<p>Jumlah screening: <b>{{ $report['screening_count'] }}</b> | Siswa unik yang sudah screening: <b>{{ $report['screened_students'] }}</b></p>

<h3>Siswa paling sering berkunjung</h3>
<table>
    <tr><th>Peringkat</th><th>Siswa</th><th>Kelas</th><th>Kunjungan</th></tr>
    @forelse($report['top_students'] as $i => $r)<tr><td>{{ $i + 1 }}</td><td>{{ $r->name }}</td><td>{{ $r->class_name }}</td><td>{{ $r->total }}</td></tr>
    @empty<tr><td colspan="4" class="muted">Belum ada data.</td></tr>@endforelse
</table>

<h3>Penyakit terbanyak</h3>
<table>
    <tr><th>Penyakit</th><th>Jumlah</th></tr>
    @forelse($report['top_diseases'] as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td colspan="2" class="muted">Belum ada data.</td></tr>@endforelse
</table>

<h3>Kelas dengan kunjungan terbanyak</h3>
<table>
    <tr><th>Kelas</th><th>Kunjungan</th></tr>
    @forelse($report['top_classes'] as $r)<tr><td>{{ $r->class_name }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td colspan="2" class="muted">Belum ada data.</td></tr>@endforelse
</table>

<h3>Obat paling banyak digunakan</h3>
<table>
    <tr><th>Obat</th><th>Jumlah unit</th></tr>
    @forelse($report['top_medicines'] as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->total }} {{ $r->unit }}</td></tr>@empty<tr><td colspan="2" class="muted">Belum ada data.</td></tr>@endforelse
</table>

<div class="row" style="align-items:flex-start">
    <div style="flex:1;min-width:280px"><h3>Indikator berat badan</h3>
        <table>@forelse($report['weight_indicators'] as $r)<tr><td>{{ $r->label }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
    <div style="flex:1;min-width:280px"><h3>Kategori tekanan darah</h3>
        <table>@forelse($report['bp_categories'] as $r)<tr><td>{{ $r->label }}</td><td>{{ $r->total }}</td></tr>@empty<tr><td class="muted">Belum ada data.</td></tr>@endforelse</table>
    </div>
</div>

<h3>Kunjungan per hari</h3>
<table>
    <tr><th>Tanggal</th><th>Jumlah</th></tr>
    @forelse($report['chart'] as $c)<tr><td>{{ $c->day }}</td><td>{{ $c->total }}</td></tr>@empty<tr><td colspan="2" class="muted">Belum ada data.</td></tr>@endforelse
</table>
@endsection