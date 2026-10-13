@extends('layouts.app')
@section('content')
<div class="row" style="justify-content:space-between"><h2>Screening</h2><a class="btn" href="{{ route('admin.screenings.create') }}">+ Tambah Screening</a></div>

<form method="GET" class="card row">
    <label>Cari siswa<input name="q" value="{{ request('q') }}" placeholder="Nama / NIS"></label>
    <label>Kelas<select name="class_id"><option value="">Semua</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id') == $c->id)>{{ $c->class_name }}</option>@endforeach</select></label>
    <label>Dari<input type="date" name="date_from" value="{{ request('date_from') }}"></label>
    <label>Sampai<input type="date" name="date_to" value="{{ request('date_to') }}"></label>
    <button class="btn">Filter</button>
</form>

<table>
    <tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>TB/BB</th><th>BMI</th><th>Indikator</th><th>Tensi</th><th></th></tr>
    @forelse($screenings as $s)
    <tr>
        <td>{{ $s->screened_at->format('d/m/Y') }}</td>
        <td>{{ $s->student->name }}<br><span class="muted">{{ $s->student->nis }}</span></td>
        <td>{{ $s->schoolClass?->class_name }}</td>
        <td>{{ $s->height_cm }} / {{ $s->weight_kg }}</td>
        <td>{{ $s->bmi }}</td><td>{{ $s->weight_indicator }}</td>
        <td>{{ $s->blood_pressure ?? '-' }}</td>
        <td><a href="{{ route('admin.screenings.show', $s) }}">Detail</a></td>
    </tr>
    @empty
    <tr><td colspan="8" class="muted">Belum ada screening.</td></tr>
    @endforelse
</table>
{{ $screenings->links() }}
@endsection