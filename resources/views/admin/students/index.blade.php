@extends('layouts.app')
@section('content')
<h2>Siswa</h2>
<details class="card"><summary>Tambah siswa (akun murid dibuat otomatis)</summary>
    <form method="POST" action="{{ route('admin.students.store') }}" class="row">
        @csrf
        <label>NIS<input name="nis" required></label>
        <label>Nama<input name="name" required></label>
        <label>Kelas<select name="class_id" required>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->class_name }}</option>@endforeach</select></label>
        <label>Jenis kelamin<select name="gender"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></label>
        <label>Tanggal lahir<input type="date" name="birth_date" required></label>
        <label>Username (kosong = NIS)<input name="username"></label>
        <label>Password (min 8)<input type="password" name="password" required></label>
        <button class="btn">Tambah</button>
    </form>
</details>

<form method="GET" class="row">
    <input name="q" value="{{ request('q') }}" placeholder="Nama / NIS">
    <select name="class_id"><option value="">Semua kelas</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id') == $c->id)>{{ $c->class_name }}</option>@endforeach</select>
    <button class="btn">Cari</button>
</form>

<table>
    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>JK</th><th>Tgl lahir</th><th>Status</th><th></th></tr>
    @forelse($students as $s)
    <tr>
        <td>{{ $s->nis }}</td><td>{{ $s->name }}</td><td>{{ $s->schoolClass?->class_name }}</td><td>{{ $s->gender }}</td>
        <td>{{ $s->birth_date?->format('d/m/Y') ?? '-' }}</td><td>{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td><details><summary>Edit</summary>
            <form method="POST" action="{{ route('admin.students.update', $s) }}">
                @csrf @method('PUT')
                <input name="nis" value="{{ $s->nis }}" required>
                <input name="name" value="{{ $s->name }}" required>
                <select name="class_id">@foreach($classes as $c)<option value="{{ $c->id }}" @selected($s->class_id == $c->id)>{{ $c->class_name }}</option>@endforeach</select>
                <select name="gender"><option value="L" @selected($s->gender === 'L')>L</option><option value="P" @selected($s->gender === 'P')>P</option></select>
                <input type="date" name="birth_date" value="{{ $s->birth_date?->format('Y-m-d') }}" required>
                <select name="is_active"><option value="1" @selected($s->is_active)>Aktif</option><option value="0" @selected(! $s->is_active)>Nonaktif</option></select>
                <button class="btn">Simpan</button>
            </form></details></td>
    </tr>
    @empty
    <tr><td colspan="7" class="muted">Belum ada siswa.</td></tr>
    @endforelse
</table>
{{ $students->links() }}
@endsection