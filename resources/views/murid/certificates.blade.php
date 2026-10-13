@extends('layouts.app')
@section('content')
<h2>Surat Keterangan</h2>
<table>
    <tr><th>Tanggal kunjungan</th><th>No. surat</th><th>Keluhan</th><th>Status</th><th></th></tr>
    @forelse($certs as $c)
    <tr>
        <td>{{ $c->visit->visited_at->format('d/m/Y') }}</td><td>{{ $c->letter_number ?? '-' }}</td><td>{{ $c->visit->complaint }}</td>
        <td>{{ $c->status_home ? 'Pulang' : 'Tidak pulang' }}</td>
        <td><a href="{{ route('murid.certificates.show', $c) }}">Lihat</a>@if(config('uks.student_can_download_pdf')) | <a href="{{ route('murid.certificates.pdf', $c) }}">PDF</a>@endif</td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada surat.</td></tr>
    @endforelse
</table>
{{ $certs->links() }}
@endsection