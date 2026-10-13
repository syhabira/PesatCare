@extends('layouts.app')
@section('content')
<h2>Unit</h2>
<form method="POST" action="{{ route('admin.units.store') }}" class="card row">
    @csrf
    <label>Nama unit<input name="unit_name" required></label>
    <label>Keterangan<input name="description"></label>
    <button class="btn">Tambah</button>
</form>
<table>
    <tr><th>Unit</th><th>Keterangan</th><th>Guru</th><th>Status</th><th></th></tr>
    @forelse($units as $u)
    <tr>
        <td>{{ $u->unit_name }}</td><td>{{ $u->description }}</td><td>{{ $u->teachers_count }}</td><td>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td><details><summary>Edit</summary>
            <form method="POST" action="{{ route('admin.units.update', $u) }}">
                @csrf @method('PUT')
                <input name="unit_name" value="{{ $u->unit_name }}" required>
                <input name="description" value="{{ $u->description }}">
                <select name="is_active"><option value="1" @selected($u->is_active)>Aktif</option><option value="0" @selected(! $u->is_active)>Nonaktif</option></select>
                <button class="btn">Simpan</button>
            </form></details></td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada unit.</td></tr>
    @endforelse
</table>
{{ $units->links() }}
@endsection