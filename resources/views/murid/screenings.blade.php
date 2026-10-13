@extends('layouts.app')
@section('content')
<h2>Riwayat Screening</h2>
@forelse($screenings as $s)
<div class="card">
    <b>{{ $s->screened_at->format('d F Y') }}</b>
    <table>
        <tr><th width="30%">TB / BB</th><td>{{ $s->height_cm }} cm / {{ $s->weight_kg }} kg</td></tr>
        <tr><th>BMI</th><td>{{ $s->bmi }} - {{ $s->weight_indicator }}</td></tr>
        <tr><th>Lingkar perut</th><td>{{ $s->waist_cm ? $s->waist_cm . ' cm' : '-' }} @if($s->waist_category)({{ $s->waist_category }})@endif</td></tr>
        <tr><th>Tekanan darah</th><td>{{ $s->blood_pressure ?? '-' }} @if($s->blood_pressure_category)({{ $s->blood_pressure_category }})@endif</td></tr>
    </table>
    @if(config('uks.student_can_download_pdf'))
        <a class="btn" href="{{ route('murid.screenings.pdf', $s) }}">Unduh PDF</a>
    @endif
</div>
@empty
<p class="muted">Belum ada data screening.</p>
@endforelse
{{ $screenings->links() }}
@endsection