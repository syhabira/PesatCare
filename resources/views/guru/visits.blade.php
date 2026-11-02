@extends('layouts.appuser')
@section('content')

<h2>Riwayat Kunjungan UKS</h2>
<p class="muted -mt-3 mb-4">Daftar kunjungan Anda ke UKS yang telah tercatat oleh petugas.</p>

<table>
    <tr>
        <th>Tanggal & Waktu</th>
        <th>Keluhan</th>
        <th>Penyakit</th>
        <th>Tindakan</th>
    </tr>
    @forelse($visits as $v)
        <tr>
            <td class="whitespace-nowrap">{{ $v->visited_at->format('d/m/Y H:i') }}</td>
            <td>{{ $v->complaint }}</td>
            <td>{{ $v->diseases->pluck('name')->join(', ') ?: '-' }}</td>
            <td class="text-slate-400">{{ $v->action ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="muted text-center py-8">Belum ada riwayat kunjungan.</td>
        </tr>
    @endforelse
</table>

{{ $visits->links() }}

@endsection