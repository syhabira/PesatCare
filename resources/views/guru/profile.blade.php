@extends('layouts.app')
@section('content')
<h2>Profil</h2>
<div class="card"><table>
    <tr><th width="30%">Nama</th><td>{{ $teacher->name }}</td></tr>
    <tr><th>NIP</th><td>{{ $teacher->nip }}</td></tr>
    <tr><th>Unit</th><td>{{ $teacher->unit?->unit_name ?? '-' }}</td></tr>
    <tr><th>Nomor LP</th><td>{{ $teacher->lp_number ?? '-' }}</td></tr>
    <tr><th>Golongan darah</th><td>{{ $teacher->blood_type ?? '-' }}</td></tr>
</table></div>
@endsection