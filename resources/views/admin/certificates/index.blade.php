@extends('layouts.app')
@section('content')
<h2>Surat Keterangan</h2>
<p class="muted">Surat dibuat dari halaman detail kunjungan.</p>
<form method="GET" class="row"><input name="q" value="{{ request('q') }}" placeholder="Nama pasien"><button class="btn">Cari</button></form>
<table>
    <tr><th>Dibuat</th><th>No. surat</th><th>Pasien</th><th>Status</th><th>Petugas</th><th></th></tr>
    @forelse($certs as $c)
    <tr>
        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
        <td>{{ $c->letter_number ?? '-' }}</td>
        <td>{{ $c->visit->patient_name }}</td>
        <td>{{ $c->status_home ? 'Pulang' : 'Tidak pulang' }}</td>
        <td>{{ $c->creator->name }}</td>
        <td><a href="{{ route('admin.certificates.show', $c) }}">Detail</a> | <a href="{{ route('admin.certificates.pdf', $c) }}">PDF</a></td>
    </tr>
    @empty
    <tr><td colspan="6" class="muted">Belum ada surat.</td></tr>
    @endforelse
</table>
{{ $certs->links() }}
@endsection