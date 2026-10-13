@extends('layouts.app')
@section('content')
@php $v = $certificate->visit; @endphp
<h2>Surat Keterangan UKS</h2>
<div class="card"><table>
    <tr><th width="30%">Nomor surat</th><td>{{ $certificate->letter_number ?? '-' }}</td></tr>
    <tr><th>Nama</th><td>{{ $v->student->name }}</td></tr>
    <tr><th>Kelas</th><td>{{ $v->student->schoolClass?->class_name }}</td></tr>
    <tr><th>Waktu kunjungan</th><td>{{ $v->visited_at->format('d/m/Y H:i') }}</td></tr>
    <tr><th>Keluhan</th><td>{{ $v->complaint }}</td></tr>
    <tr><th>Tindakan</th><td>{{ $v->action ?? '-' }}</td></tr>
    <tr><th>Status</th><td>{{ $certificate->status_home ? 'Pulang' : 'Tidak pulang' }}</td></tr>
    <tr><th>Waktu pulang</th><td>{{ $certificate->departure_time?->format('d/m/Y H:i') ?? '-' }}</td></tr>
    <tr><th>Catatan</th><td>{{ $certificate->additional_notes ?? '-' }}</td></tr>
</table>
@if(config('uks.student_can_download_pdf'))<a class="btn" href="{{ route('murid.certificates.pdf', $certificate) }}">Unduh PDF</a>@endif
</div>
<a href="{{ route('murid.certificates') }}">&larr; Kembali</a>
@endsection