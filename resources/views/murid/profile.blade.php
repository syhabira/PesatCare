@extends('layouts.app')
@section('content')
<h2>Profil Kesehatan</h2>
<div class="card"><table>
    <tr><th width="30%">Nama</th><td>{{ $student->name }}</td></tr>
    <tr><th>NIS</th><td>{{ $student->nis }}</td></tr>
    <tr><th>Kelas</th><td>{{ $student->schoolClass?->class_name }}</td></tr>
    <tr><th>Jenis kelamin</th><td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
</table></div>

<h3>Screening terbaru</h3>
@if($latestScreening)
<div class="card"><table>
    <tr><th width="30%">Tanggal</th><td>{{ $latestScreening->screened_at->format('d/m/Y') }}</td></tr>
    <tr><th>Tinggi badan</th><td>{{ $latestScreening->height_cm }} cm</td></tr>
    <tr><th>Berat badan</th><td>{{ $latestScreening->weight_kg }} kg</td></tr>
    <tr><th>BMI</th><td>{{ $latestScreening->bmi }}</td></tr>
    <tr><th>Indikator BMI</th><td>{{ $latestScreening->weight_indicator }}</td></tr>
    <tr><th>Lingkar perut</th><td>{{ $latestScreening->waist_cm ? $latestScreening->waist_cm . ' cm' : '-' }}</td></tr>
    <tr><th>Kategori lingkar perut</th><td>{{ $latestScreening->waist_category ?? '-' }}</td></tr>
    <tr><th>Tekanan darah</th><td>{{ $latestScreening->blood_pressure ?? '-' }}</td></tr>
    <tr><th>Kategori tekanan darah</th><td>{{ $latestScreening->blood_pressure_category ?? '-' }}</td></tr>
</table>
<p class="muted">Hasil ini merupakan indikator skrining, bukan diagnosis.</p></div>
@else
<p class="muted">Belum ada data screening.</p>
@endif
@endsection