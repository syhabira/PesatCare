@extends('layouts.app')
@section('content')
<h2>Riwayat Kunjungan</h2>
<table>
    <tr><th>Tanggal</th><th>Keluhan</th><th>Penyakit</th><th>Tindakan</th></tr>
    @forelse($visits as $v)
    <tr><td>{{ $v->visited_at->format('d/m/Y') }}</td><td>{{ $v->complaint }}</td><td>{{ $v->diseases->pluck('name')->join(', ') }}</td><td>{{ $v->action ?? '-' }}</td></tr>
    @empty
    <tr><td colspan="4" class="muted">Belum ada kunjungan.</td></tr>
    @endforelse
</table>
{{ $visits->links() }}
@endsection