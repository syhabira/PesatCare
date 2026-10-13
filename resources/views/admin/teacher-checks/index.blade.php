@extends('layouts.app')
@section('content')
<h2>Pemeriksaan Kesehatan Guru</h2>

<form method="POST" action="{{ route('admin.teacher-checks.store') }}" class="card">
    @csrf
    <b>Tambah pemeriksaan</b>
    @include('partials.patient-search', ['name' => 'teacher_id', 'type' => 'guru'])
    <div class="row" style="margin-top:8px">
        <label>Tanggal<input type="date" name="checked_at" value="{{ now()->format('Y-m-d') }}" required></label>
        <label>Asam urat (mg/dL)<input type="number" step="0.1" name="uric_acid" style="width:100px"></label>
        <label>Kolesterol (mg/dL)<input type="number" name="cholesterol" style="width:100px"></label>
        <label>Catatan<input name="notes"></label>
        <button class="btn">Simpan</button>
    </div>
</form>

<form method="GET" class="row">
    <input name="q" value="{{ request('q') }}" placeholder="Nama / NIP">
    <input type="date" name="date_from" value="{{ request('date_from') }}">
    <input type="date" name="date_to" value="{{ request('date_to') }}">
    <button class="btn">Filter</button>
</form>

<table>
    <tr><th>Tanggal</th><th>Guru</th><th>Unit</th><th>No. LP</th><th>Gol. darah</th><th>Asam urat</th><th>Kolesterol</th><th>Petugas</th><th>Aksi</th></tr>
    @forelse($checks as $c)
    <tr>
        <td>{{ $c->checked_at->format('d/m/Y') }}</td>
        <td><a href="{{ route('admin.teacher-checks.history', $c->teacher) }}">{{ $c->teacher->name }}</a><br><span class="muted">{{ $c->teacher->nip }}</span></td>
        <td>{{ $c->teacher->unit?->unit_name }}</td><td>{{ $c->teacher->lp_number }}</td><td>{{ $c->teacher->blood_type }}</td>
        <td>{{ $c->uric_acid ?? '-' }}</td><td>{{ $c->cholesterol ?? '-' }}</td><td>{{ $c->recorder->name }}</td>
        <td>
            <details><summary>Edit</summary>
                <form method="POST" action="{{ route('admin.teacher-checks.update', $c) }}">
                    @csrf @method('PUT')
                    <input type="date" name="checked_at" value="{{ $c->checked_at->format('Y-m-d') }}" required>
                    <input type="number" step="0.1" name="uric_acid" value="{{ $c->uric_acid }}" placeholder="Asam urat" style="width:90px">
                    <input type="number" name="cholesterol" value="{{ $c->cholesterol }}" placeholder="Kolesterol" style="width:90px">
                    <input name="notes" value="{{ $c->notes }}" placeholder="Catatan">
                    <button class="btn">Simpan</button>
                </form>
            </details>
        </td>
    </tr>
    @empty
    <tr><td colspan="9" class="muted">Belum ada data.</td></tr>
    @endforelse
</table>
{{ $checks->links() }}
@endsection