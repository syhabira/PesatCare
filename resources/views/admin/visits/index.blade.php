@extends('layouts.app')
@section('content')
<div class="row" style="justify-content:space-between"><h2>Kunjungan</h2><a class="btn" href="{{ route('admin.visits.create') }}">+ Tambah Kunjungan</a></div>

<form method="GET" class="card row">
    <label>Cari<input name="q" value="{{ request('q') }}" placeholder="Nama / NIS / NIP"></label>
    <label>Dari<input type="date" name="date_from" value="{{ request('date_from') }}"></label>
    <label>Sampai<input type="date" name="date_to" value="{{ request('date_to') }}"></label>
    <label>Kelas<select name="class_id"><option value="">Semua</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected(request('class_id') == $c->id)>{{ $c->class_name }}</option>@endforeach</select></label>
    <label>Penyakit<select name="disease_id"><option value="">Semua</option>@foreach($diseases as $d)<option value="{{ $d->id }}" @selected(request('disease_id') == $d->id)>{{ $d->name }}</option>@endforeach</select></label>
    <label>Petugas<select name="recorded_by"><option value="">Semua</option>@foreach($officers as $o)<option value="{{ $o->id }}" @selected(request('recorded_by') == $o->id)>{{ $o->name }}</option>@endforeach</select></label>
    <label>Triase<select name="triage_level"><option value="">Semua</option>@foreach(['hijau','kuning','merah'] as $l)<option value="{{ $l }}" @selected(request('triage_level') === $l)>{{ ucfirst($l) }}</option>@endforeach</select></label>
    <button class="btn">Filter</button>
    <a href="{{ route('admin.visits.index') }}">Reset</a>
</form>

<table>
    <tr><th>Waktu</th><th>Pasien</th><th>Kelas/Unit</th><th>Penyakit</th><th>Triase</th><th>Petugas</th><th></th></tr>
    @forelse($visits as $v)
    <tr>
        <td>{{ $v->visited_at->format('d/m/Y H:i') }}</td>
        <td>{{ $v->patient_name }}<br><span class="muted">{{ $v->student?->nis ?? $v->teacher?->nip }}</span></td>
        <td>{{ $v->schoolClass?->class_name ?? $v->unit?->unit_name }}</td>
        <td>{{ $v->diseases->pluck('name')->join(', ') }}</td>
        <td><x-triage :level="$v->triage_level" /></td>
        <td>{{ $v->recorder->name }}</td>
        <td><a href="{{ route('admin.visits.show', $v) }}">Detail</a></td>
    </tr>
    @empty
    <tr><td colspan="7" class="muted">Belum ada kunjungan.</td></tr>
    @endforelse
</table>
{{ $visits->links() }}
@endsection