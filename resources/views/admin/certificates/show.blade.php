@extends('layouts.app')
@section('content')
@php $v = $certificate->visit; @endphp
<div class="row" style="justify-content:space-between">
    <h2>Surat Keterangan UKS</h2>
    <div class="row"><a class="btn" href="{{ route('admin.certificates.pdf', $certificate) }}">Unduh PDF</a><a class="btn" href="javascript:window.print()">Cetak</a></div>
</div>
<div class="card">
<table>
    <tr><th width="30%">Nomor surat</th><td>{{ $certificate->letter_number ?? '-' }}</td></tr>
    <tr><th>Nama</th><td>{{ $v->patient_name }}</td></tr>
    <tr><th>Kelas / Unit</th><td>{{ $v->student?->schoolClass?->class_name ?? $v->teacher?->unit?->unit_name ?? '-' }}</td></tr>
    <tr><th>Waktu kunjungan</th><td>{{ $v->visited_at->format('d/m/Y H:i') }} (<a href="{{ route('admin.visits.show', $v) }}">lihat kunjungan</a>)</td></tr>
    <tr><th>Suhu</th><td>{{ $v->temperature ? $v->temperature . ' °C' : '-' }}</td></tr>
    <tr><th>Tekanan darah</th><td>{{ $v->blood_pressure ?? '-' }}</td></tr>
    <tr><th>Nadi</th><td>{{ $v->pulse ?? '-' }}</td></tr>
    <tr><th>Anamnesa</th><td>{{ $v->anamnesis ?? '-' }}</td></tr>
    <tr><th>Keluhan</th><td>{{ $v->complaint }}</td></tr>
    <tr><th>Tindakan</th><td>{{ $v->action ?? '-' }}</td></tr>
    <tr><th>Status</th><td>{{ $certificate->status_home ? 'Pulang' : 'Tidak pulang' }}</td></tr>
    <tr><th>Waktu pulang</th><td>{{ $certificate->departure_time?->format('d/m/Y H:i') ?? '-' }}</td></tr>
    <tr><th>Catatan tambahan</th><td>{{ $certificate->additional_notes ?? '-' }}</td></tr>
    <tr><th>Dibuat oleh</th><td>{{ $certificate->creator->name }}</td></tr>
</table>
</div>

<details class="card"><summary>Edit surat</summary>
    <form method="POST" action="{{ route('admin.certificates.update', $certificate) }}" class="row">
        @csrf @method('PUT')
        <label>Nomor surat<input name="letter_number" value="{{ old('letter_number', $certificate->letter_number) }}"></label>
        <label>Status<select name="status_home"><option value="0" @selected(! $certificate->status_home)>Tidak pulang</option><option value="1" @selected($certificate->status_home)>Pulang</option></select></label>
        <label>Waktu pulang<input type="datetime-local" name="departure_time" value="{{ $certificate->departure_time?->format('Y-m-d\TH:i') }}"></label>
        <label>Catatan tambahan<input name="additional_notes" value="{{ old('additional_notes', $certificate->additional_notes) }}"></label>
        <button class="btn">Simpan</button>
    </form>
</details>
<a href="{{ route('admin.certificates.index') }}">&larr; Kembali</a>
@endsection