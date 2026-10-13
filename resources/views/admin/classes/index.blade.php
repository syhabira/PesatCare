@extends('layouts.app')
@section('content')
<h2>Kelas</h2>
<form method="POST" action="{{ route('admin.classes.store') }}" class="card row">
    @csrf
    <label>Nama kelas<input name="class_name" placeholder="XII RPL 1" required></label>
    <label>Tingkat<input type="number" name="grade_level" min="1" max="13" required style="width:80px"></label>
    <label>Jenjang<select name="school_level"><option>SMK</option><option>SMA</option><option>SMP</option></select></label>
    <label>Tahun ajaran<input name="academic_year" placeholder="2026/2027" required style="width:110px"></label>
    <button class="btn">Tambah</button>
</form>
<table>
    <tr><th>Kelas</th><th>Tingkat</th><th>Jenjang</th><th>Tahun ajaran</th><th>Siswa</th><th>Status</th><th></th></tr>
    @forelse($classes as $c)
    <tr>
        <td>{{ $c->class_name }}</td><td>{{ $c->grade_level }}</td><td>{{ $c->school_level }}</td><td>{{ $c->academic_year }}</td>
        <td>{{ $c->students_count }}</td><td>{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td><details><summary>Edit</summary>
            <form method="POST" action="{{ route('admin.classes.update', $c) }}">
                @csrf @method('PUT')
                <input name="class_name" value="{{ $c->class_name }}" required>
                <input type="number" name="grade_level" value="{{ $c->grade_level }}" required style="width:70px">
                <select name="school_level">@foreach(['SMK', 'SMA', 'SMP'] as $l)<option @selected($c->school_level === $l)>{{ $l }}</option>@endforeach</select>
                <input name="academic_year" value="{{ $c->academic_year }}" required style="width:100px">
                <select name="is_active"><option value="1" @selected($c->is_active)>Aktif</option><option value="0" @selected(! $c->is_active)>Nonaktif</option></select>
                <button class="btn">Simpan</button>
            </form></details></td>
    </tr>
    @empty
    <tr><td colspan="7" class="muted">Belum ada kelas.</td></tr>
    @endforelse
</table>
{{ $classes->links() }}
@endsection