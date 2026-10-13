@extends('layouts.app')
@section('content')
<h2>Riwayat Pemeriksaan Kesehatan</h2>
<table>
    <tr><th>Tanggal</th><th>Asam urat</th><th>Kolesterol</th><th>Gol. darah</th><th>Catatan</th></tr>
    @forelse($checks as $c)
    <tr><td>{{ $c->checked_at->format('d/m/Y') }}</td><td>{{ $c->uric_acid ?? '-' }}</td><td>{{ $c->cholesterol ?? '-' }}</td><td>{{ $teacher->blood_type ?? '-' }}</td><td>{{ $c->notes }}</td></tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada pemeriksaan.</td></tr>
    @endforelse
</table>
{{ $checks->links() }}
@endsection