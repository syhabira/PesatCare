@extends('layouts.app')
@section('content')
<h2>Riwayat Pemeriksaan: {{ $teacher->name }}</h2>
<p>NIP {{ $teacher->nip }} | Unit {{ $teacher->unit?->unit_name ?? '-' }} | No. LP {{ $teacher->lp_number ?? '-' }} | Gol. darah {{ $teacher->blood_type ?? '-' }}</p>
<table>
    <tr><th>Tanggal</th><th>Asam urat</th><th>Kolesterol</th><th>Catatan</th></tr>
    @forelse($checks as $c)
    <tr><td>{{ $c->checked_at->format('d/m/Y') }}</td><td>{{ $c->uric_acid ?? '-' }}</td><td>{{ $c->cholesterol ?? '-' }}</td><td>{{ $c->notes }}</td></tr>
    @empty
    <tr><td colspan="4" class="muted">Belum ada pemeriksaan.</td></tr>
    @endforelse
</table>
{{ $checks->links() }}
<a href="{{ route('admin.teacher-checks.index') }}">&larr; Kembali</a>
@endsection