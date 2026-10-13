@extends('layouts.app')
@section('content')
<h2>Guru</h2>
<details class="card"><summary>Tambah guru (akun guru dibuat otomatis)</summary>
    <form method="POST" action="{{ route('admin.teachers.store') }}" class="row">
        @csrf
        <label>NIP<input name="nip" required></label>
        <label>Nama<input name="name" required></label>
        <label>Unit<select name="unit_id"><option value="">-</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->unit_name }}</option>@endforeach</select></label>
        <label>No. LP<input name="lp_number"></label>
        <label>Gol. darah<select name="blood_type"><option value="">-</option>@foreach(['A', 'B', 'AB', 'O'] as $b)<option>{{ $b }}</option>@endforeach</select></label>
        <label>Username (kosong = NIP)<input name="username"></label>
        <label>Password (min 8)<input type="password" name="password" required></label>
        <button class="btn">Tambah</button>
    </form>
</details>

<form method="GET" class="row"><input name="q" value="{{ request('q') }}" placeholder="Nama / NIP"><button class="btn">Cari</button></form>

<table>
    <tr><th>NIP</th><th>Nama</th><th>Unit</th><th>No. LP</th><th>Gol. darah</th><th>Status</th><th></th></tr>
    @forelse($teachers as $t)
    <tr>
        <td>{{ $t->nip }}</td><td>{{ $t->name }}</td><td>{{ $t->unit?->unit_name }}</td><td>{{ $t->lp_number }}</td><td>{{ $t->blood_type }}</td>
        <td>{{ $t->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td><details><summary>Edit</summary>
            <form method="POST" action="{{ route('admin.teachers.update', $t) }}">
                @csrf @method('PUT')
                <input name="nip" value="{{ $t->nip }}" required>
                <input name="name" value="{{ $t->name }}" required>
                <select name="unit_id"><option value="">-</option>@foreach($units as $u)<option value="{{ $u->id }}" @selected($t->unit_id == $u->id)>{{ $u->unit_name }}</option>@endforeach</select>
                <input name="lp_number" value="{{ $t->lp_number }}" placeholder="No. LP">
                <select name="blood_type"><option value="">-</option>@foreach(['A', 'B', 'AB', 'O'] as $b)<option @selected($t->blood_type === $b)>{{ $b }}</option>@endforeach</select>
                <select name="is_active"><option value="1" @selected($t->is_active)>Aktif</option><option value="0" @selected(! $t->is_active)>Nonaktif</option></select>
                <button class="btn">Simpan</button>
            </form></details></td>
    </tr>
    @empty
    <tr><td colspan="7" class="muted">Belum ada guru.</td></tr>
    @endforelse
</table>
{{ $teachers->links() }}
@endsection