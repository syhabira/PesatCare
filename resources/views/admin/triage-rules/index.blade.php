@extends('layouts.app')
@section('content')
<h2>Aturan Triase</h2>
<p class="muted">Nilai di bawah adalah aturan yang dapat diubah sesuai SOP UKS. Mengubah aturan menaikkan versi dan mencabut status "disahkan". Kunjungan lama tetap memakai aturan saat dicatat.</p>

<details class="card" open>
    <summary>Tambah aturan</summary>
    <form method="POST" action="{{ route('admin.triage-rules.store') }}">
        @csrf
        @include('admin.triage-rules._form', ['r' => null])
        <button class="btn">Tambah</button>
    </form>
</details>

<table>
    <tr><th>Nama</th><th>Kondisi</th><th>Level</th><th>Batas (mnt)</th><th>Status</th><th>Aksi</th></tr>
    @forelse($rules as $r)
    <tr>
        <td>{{ $r->name }}<br><span class="muted">v{{ $r->version }}</span></td>
        <td>
            @if($r->parameter === 'disease') Penyakit: {{ $r->disease?->name }}
            @else {{ $r->parameter }} {{ ['gte' => '>=', 'gt' => '>', 'lte' => '<=', 'lt' => '<', 'between' => 'antara'][$r->operator] ?? '' }} {{ $r->value + 0 }}@if($r->operator === 'between') s/d {{ $r->value_max + 0 }}@endif
            @endif
        </td>
        <td><x-triage :level="$r->level" /></td>
        <td>{{ $r->deadline_minutes ?? '-' }}</td>
        <td>
            {{ $r->is_active ? 'Aktif' : 'Nonaktif' }}<br>
            @if($r->is_validated)<span style="color:#15803d">Disahkan {{ $r->validated_at?->format('d/m/Y') }}</span>@else<b style="color:#b45309">Belum disahkan</b>@endif
        </td>
        <td>
            <details><summary>Edit</summary>
                <form method="POST" action="{{ route('admin.triage-rules.update', $r) }}">
                    @csrf @method('PUT')
                    @include('admin.triage-rules._form', ['r' => $r])
                    <button class="btn">Simpan</button>
                </form>
            </details>
            <form method="POST" action="{{ route('admin.triage-rules.toggle', $r) }}" style="display:inline">@csrf @method('PATCH') <button class="btn">{{ $r->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
            @unless($r->is_validated)
            <form method="POST" action="{{ route('admin.triage-rules.validate', $r) }}" style="display:inline" onsubmit="return confirm('Tandai aturan ini sudah disahkan pihak sekolah?')">@csrf @method('PATCH') <button class="btn">Tandai disahkan</button></form>
            @endunless
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="muted">Belum ada aturan.</td></tr>
    @endforelse
</table>
@endsection