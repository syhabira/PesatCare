@extends('layouts.appuser')
@section('content')

<h2>Profil Kesehatan</h2>

<div class="card" style="max-width:560px">
    <table>
        <tr>
            <th width="35%">Nama Lengkap</th>
            <td>{{ $student->name }}</td>
        </tr>
        <tr>
            <th>NIS</th>
            <td>{{ $student->nis }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $student->schoolClass?->class_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <th>Tanggal Lahir</th>
            <td>{{ $student->birth_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($student->is_active)
                    <span class="ok" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem">Aktif</span>
                @else
                    <span class="bad" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem">Nonaktif</span>
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- Screening Terbaru --}}
<h3>Data Screening Terbaru</h3>

@if($latestScreening)
<div class="card" style="max-width:560px">
    <table>
        <tr>
            <th width="35%">Tanggal Screening</th>
            <td>{{ $latestScreening->screened_at->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Tinggi Badan</th>
            <td>{{ $latestScreening->height_cm }} cm</td>
        </tr>
        <tr>
            <th>Berat Badan</th>
            <td>{{ $latestScreening->weight_kg }} kg</td>
        </tr>
        <tr>
            <th>BMI</th>
            <td>{{ $latestScreening->bmi }}</td>
        </tr>
        <tr>
            <th>Indikator BMI</th>
            <td>{{ $latestScreening->weight_indicator }}</td>
        </tr>
        <tr>
            <th>Lingkar Perut</th>
            <td>
                {{ $latestScreening->waist_cm ? $latestScreening->waist_cm . ' cm' : '-' }}
                @if($latestScreening->waist_category)
                    <span class="muted">({{ $latestScreening->waist_category }})</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Tekanan Darah</th>
            <td>
                {{ $latestScreening->blood_pressure ?? '-' }}
                @if($latestScreening->blood_pressure_category)
                    <span class="muted">({{ $latestScreening->blood_pressure_category }})</span>
                @endif
            </td>
        </tr>
    </table>
    <p class="muted text-xs mt-3">Hasil ini merupakan indikator skrining, bukan diagnosis medis.</p>
</div>
@else
    <p class="muted">Belum ada data screening yang tercatat.</p>
@endif

@endsection