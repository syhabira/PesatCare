@extends('layouts.app')
@section('content')
<h2>Halo, {{ $student->name }}</h2>
<div class="row">
    <div class="card">Kelas<br><b>{{ $student->schoolClass?->class_name }}</b></div>
    <div class="card">Total kunjungan<br><b>{{ $visitCount }}</b></div>
    <div class="card">Screening terakhir<br><b>{{ $latestScreening?->screened_at->format('d/m/Y') ?? 'Belum ada' }}</b></div>
</div>
@if($latestScreening)
<div class="card">
    <b>Ringkasan screening terakhir</b>
    <p>BMI: {{ $latestScreening->bmi }} - {{ $latestScreening->weight_indicator }}<br>
    Tekanan darah: {{ $latestScreening->blood_pressure ?? '-' }} @if($latestScreening->blood_pressure_category)({{ $latestScreening->blood_pressure_category }})@endif</p>
</div>
@endif
@endsection