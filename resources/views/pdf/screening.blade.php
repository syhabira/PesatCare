<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8">
<style>
  body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#14213d}
  h1{font-size:18px;margin:0;color:#3368A0} .muted{color:#666}
  table{width:100%;border-collapse:collapse;margin-top:12px}
  td,th{border:1px solid #C8DFDB;padding:6px 8px;text-align:left} th{background:#C8DFDB;width:35%}
</style></head><body>
<h1>Hasil Screening Kesehatan</h1>
<div class="muted">SMK Informatika Pesat Kota Bogor — UKS</div>
<table>
  <tr><th>Nama</th><td>{{ $screening->student->name }}</td></tr>
  <tr><th>NIS</th><td>{{ $screening->student->nis }}</td></tr>
  <tr><th>Kelas</th><td>{{ $screening->schoolClass?->class_name ?? $screening->student->schoolClass?->class_name }}</td></tr>
  <tr><th>Tanggal Screening</th><td>{{ $screening->screened_at->translatedFormat('d F Y') }}</td></tr>
  <tr><th>Tinggi Badan</th><td>{{ $screening->height_cm }} cm</td></tr>
  <tr><th>Berat Badan</th><td>{{ $screening->weight_kg }} kg</td></tr>
  <tr><th>BMI</th><td>{{ $screening->bmi }}</td></tr>
  <tr><th>Indikator BMI</th><td>{{ $screening->weight_indicator }}</td></tr>
  <tr><th>Lingkar Perut</th><td>{{ $screening->waist_cm ? $screening->waist_cm.' cm' : '-' }}</td></tr>
  <tr><th>Kategori Lingkar Perut</th><td>{{ $screening->waist_category ?? '-' }}</td></tr>
  <tr><th>Tekanan Darah</th><td>{{ $screening->blood_pressure ?? '-' }}</td></tr>
  <tr><th>Kategori Tekanan Darah</th><td>{{ $screening->blood_pressure_category ?? '-' }}</td></tr>
</table>
<p class="muted">Hasil ini merupakan indikator skrining, bukan diagnosis. Dicatat oleh {{ $screening->recorder->name }}.</p>
</body></html>