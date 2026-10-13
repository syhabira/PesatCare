@extends('layouts.app')
@section('content')
<div class="row" style="justify-content:space-between">
    <h2>Detail Screening</h2>
    <div class="row">
        <a class="btn" href="{{ route('admin.screenings.edit', $screening) }}">Edit</a>
        <a class="btn" href="{{ route('admin.screenings.pdf', $screening) }}">Unduh PDF</a>
        <a class="btn" href="javascript:window.print()">Cetak</a>
    </div>
</div>
<div class="card">
<table>
    <tr><th width="30%">Nama</th><td>{{ $screening->student->name }}</td></tr>
    <tr><th>NIS</th><td>{{ $screening->student->nis }}</td></tr>
    <tr><th>Kelas</th><td>{{ $screening->schoolClass?->class_name ?? $screening->student->schoolClass?->class_name }}</td></tr>
    <tr><th>Jenis kelamin</th><td>{{ $screening->student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
    <tr><th>Tanggal screening</th><td>{{ $screening->screened_at->format('d/m/Y') }}</td></tr>
    <tr><th>Usia saat screening</th><td>{{ $screening->age_months !== null ? intdiv($screening->age_months, 12) . ' th ' . ($screening->age_months % 12) . ' bln' : 'Tanggal lahir belum diisi' }}</td></tr>
    <tr><th>Tinggi badan</th><td>{{ $screening->height_cm }} cm</td></tr>
    <tr><th>Berat badan</th><td>{{ $screening->weight_kg }} kg</td></tr>
    <tr><th>BMI</th><td>{{ $screening->bmi }}</td></tr>
    <tr><th>Indikator BMI</th><td>{{ $screening->weight_indicator }}</td></tr>
    <tr><th>Lingkar perut</th><td>{{ $screening->waist_cm ? $screening->waist_cm . ' cm' : '-' }}</td></tr>
    <tr><th>Kategori lingkar perut</th><td>{{ $screening->waist_category ?? '-' }}</td></tr>
    <tr><th>Tekanan darah</th><td>{{ $screening->blood_pressure ?? '-' }}</td></tr>
    <tr><th>Kategori tekanan darah</th><td>{{ $screening->blood_pressure_category ?? '-' }}</td></tr>
    <tr><th>Dicatat oleh</th><td>{{ $screening->recorder->name }}</td></tr>
</table>
<p class="muted">Hasil ini merupakan indikator skrining, bukan diagnosis.</p>
</div>
<a href="{{ route('admin.screenings.index') }}">&larr; Kembali</a>
@endsection