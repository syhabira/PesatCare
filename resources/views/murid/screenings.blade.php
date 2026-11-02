@extends('layouts.appuser')
@section('content')

<h2>Riwayat Screening Kesehatan</h2>
<p class="muted -mt-3 mb-4">Data hasil pemeriksaan skrining kesehatan Anda.</p>

@forelse($screenings as $s)
<div class="card" style="margin-bottom:1rem">
    <div class="flex items-center justify-between mb-3">
        <p class="font-semibold text-slate-700">{{ $s->screened_at->format('d F Y') }}</p>
        @if(config('uks.student_can_download_pdf'))
            <a class="btn" href="{{ route('murid.screenings.pdf', $s) }}" style="padding:0.375rem 1rem;font-size:0.8125rem">
                Unduh PDF
            </a>
        @endif
    </div>
    <table>
        <tr>
            <th width="35%">Tinggi / Berat</th>
            <td>{{ $s->height_cm }} cm / {{ $s->weight_kg }} kg</td>
        </tr>
        <tr>
            <th>BMI</th>
            <td>{{ $s->bmi }} &mdash; {{ $s->weight_indicator }}</td>
        </tr>
        <tr>
            <th>Lingkar Perut</th>
            <td>
                {{ $s->waist_cm ? $s->waist_cm . ' cm' : '-' }}
                @if($s->waist_category)
                    <span class="muted">({{ $s->waist_category }})</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Tekanan Darah</th>
            <td>
                {{ $s->blood_pressure ?? '-' }}
                @if($s->blood_pressure_category)
                    <span class="muted">({{ $s->blood_pressure_category }})</span>
                @endif
            </td>
        </tr>
    </table>
</div>
@empty
    <p class="muted">Belum ada data screening yang tercatat.</p>
@endforelse

{{ $screenings->links() }}

@endsection