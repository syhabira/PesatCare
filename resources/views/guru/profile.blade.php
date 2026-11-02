@extends('layouts.appuser')
@section('content')

<h2>Profil Saya</h2>

<div class="card" style="max-width:560px">
    <table>
        <tr>
            <th width="35%">Nama Lengkap</th>
            <td>{{ $teacher->name }}</td>
        </tr>
        <tr>
            <th>NIP</th>
            <td>{{ $teacher->nip }}</td>
        </tr>
        <tr>
            <th>Unit / Bidang</th>
            <td>{{ $teacher->unit?->unit_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nomor LP</th>
            <td>{{ $teacher->lp_number ?? '-' }}</td>
        </tr>
        <tr>
            <th>Golongan Darah</th>
            <td>{{ $teacher->blood_type ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($teacher->is_active)
                    <span class="ok" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem">Aktif</span>
                @else
                    <span class="bad" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem">Nonaktif</span>
                @endif
            </td>
        </tr>
    </table>
</div>

@endsection