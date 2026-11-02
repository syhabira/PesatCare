@extends('layouts.appuser')
@section('content')
@php $v = $certificate->visit; @endphp

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('murid.certificates') }}" class="btn" style="padding:0.4rem 0.9rem;font-size:0.8125rem;background:#64748b">&larr; Kembali</a>
    @if(config('uks.student_can_download_pdf'))
        <a href="{{ route('murid.certificates.pdf', $certificate) }}" class="btn" style="padding:0.4rem 0.9rem;font-size:0.8125rem">Unduh PDF</a>
    @endif
</div>

<h2>Detail Surat Keterangan UKS</h2>

<div class="card" style="max-width:560px">
    <table>
        <tr>
            <th width="35%">Nomor Surat</th>
            <td>{{ $certificate->letter_number ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nama Siswa</th>
            <td>{{ $v->student->name }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $v->student->schoolClass?->class_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Waktu Kunjungan</th>
            <td>{{ $v->visited_at->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <th>Keluhan</th>
            <td>{{ $v->complaint }}</td>
        </tr>
        <tr>
            <th>Tindakan</th>
            <td>{{ $v->action ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($certificate->status_home)
                    <span class="ok" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem">Pulang</span>
                @else
                    <span class="muted">Tidak pulang</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Waktu Pulang</th>
            <td>{{ $certificate->departure_time?->format('d F Y, H:i') ?? '-' }}</td>
        </tr>
        @if($certificate->additional_notes)
        <tr>
            <th>Catatan Tambahan</th>
            <td>{{ $certificate->additional_notes }}</td>
        </tr>
        @endif
    </table>
</div>

@endsection