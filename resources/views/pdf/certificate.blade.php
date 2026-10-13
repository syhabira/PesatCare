@php($v = $certificate->visit)
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8">
<style>
  body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#14213d}
  h1{font-size:18px;text-align:center;color:#3368A0}
  table{width:100%;border-collapse:collapse;margin-top:10px} td{padding:4px 6px;vertical-align:top} td:first-child{width:30%}
</style></head><body>
<div style="text-align:center">
  <strong>{{ config('uks.school.name') }}</strong><br>
  @if(config('uks.school.address')){{ config('uks.school.address') }}<br>@endif
</div>
<hr>
<h1>SURAT KETERANGAN UKS</h1>
@if($certificate->letter_number)
<p style="text-align:center">Nomor: {{ $certificate->letter_number }}</p>
@endif
<table>
  <tr><td>Nama</td><td>: {{ $v->patient_name }}</td></tr>
  <tr><td>Kelas / Unit</td><td>: {{ $v->student?->schoolClass?->class_name ?? $v->teacher?->unit?->unit_name ?? '-' }}</td></tr>
  <tr><td>Waktu Kunjungan</td><td>: {{ $v->visited_at->translatedFormat('d F Y H:i') }}</td></tr>
  <tr><td>Keluhan</td><td>: {{ $v->complaint }}</td></tr>
  <tr><td>Anamnesa</td><td>: {{ $v->anamnesis ?? '-' }}</td></tr>
  <tr><td>Suhu</td><td>: {{ $v->temperature ? $v->temperature.' °C' : '-' }}</td></tr>
  <tr><td>Tekanan Darah</td><td>: {{ $v->blood_pressure ?? '-' }}</td></tr>
  <tr><td>Nadi</td><td>: {{ $v->pulse ? $v->pulse.' x/menit' : '-' }}</td></tr>
  <tr><td>Tindakan</td><td>: {{ $v->action ?? '-' }}</td></tr>
  <tr><td>Status</td><td>: {{ $certificate->status_home ? 'Pulang' : 'Tidak pulang' }}</td></tr>
  @if($certificate->status_home)
  <tr><td>Waktu Pulang</td><td>: {{ $certificate->departure_time?->translatedFormat('d F Y H:i') }}</td></tr>
  @endif
  <tr><td>Catatan Tambahan</td><td>: {{ $certificate->additional_notes ?? '-' }}</td></tr>
</table>
<div style="margin-top:30px;text-align:right">
  {{ config('uks.school.signer_title') }}<br><br><br>
  <strong>{{ config('uks.school.signer_name') ?: $certificate->creator->name }}</strong>
</div>
</body></html>