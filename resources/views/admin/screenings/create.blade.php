@extends('layouts.app')
@section('content')
<h2>Tambah Screening</h2>
@include('admin.screenings._form', ['action' => route('admin.screenings.store'), 'method' => 'POST'])
@endsection