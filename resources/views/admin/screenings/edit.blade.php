@extends('layouts.app')
@section('content')
<h2>Edit Screening</h2>
<p>Siswa: <b>{{ $screening->student->name }}</b> ({{ $screening->student->nis }}) - siswa tidak dapat diganti.</p>
@include('admin.screenings._form', ['action' => route('admin.screenings.update', $screening), 'method' => 'PUT'])
@endsection