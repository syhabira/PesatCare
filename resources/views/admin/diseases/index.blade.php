@extends('layouts.app')
@section('content')
<h2>Penyakit</h2>

<form method="POST" action="{{ route('admin.diseases.store') }}" class="card row">
    @csrf
    <label>Nama<input name="name" required></label>
    <label>Keterangan<input name="description"></label>
    <button class="btn">Tambah</button>
</form>

<form method="GET" class="row">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama">
    <select name="active"><option value="">Semua</option><option value="1" @selected(request('active') === '1')>Aktif</option><option value="0" @selected(request('active') === '0')>Nonaktif</option></select>
    <button class="btn">Cari</button>
</form>

<table>
    <tr><th>Nama</th><th>Keterangan</th><th>Dipakai</th><th>Status</th><th>Aksi</th></tr>
    @forelse($diseases as $d)
    <tr>
        <td>{{ $d->name }}</td><td>{{ $d->description }}</td><td>{{ $d->visits_count }}x</td>
        <td>{{ $d->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td>
            <details><summary>Edit</summary>
                <form method="POST" action="{{ route('admin.diseases.update', $d) }}">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $d->name }}" required>
                    <input name="description" value="{{ $d->description }}">
                    <select name="is_active"><option value="1" @selected($d->is_active)>Aktif</option><option value="0" @selected(! $d->is_active)>Nonaktif</option></select>
                    <button class="btn">Simpan</button>
                </form>
            </details>
            <form method="POST" action="{{ route('admin.diseases.toggle', $d) }}">@csrf @method('PATCH') <button class="btn">{{ $d->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
    @endforelse
</table>
{{ $diseases->links() }}
@endsection