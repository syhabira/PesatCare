@extends('layouts.app')
@section('content')
<h2>Tambah Kunjungan</h2>
@include('admin.visits._form', ['action' => route('admin.visits.store'), 'method' => 'POST'])
@endsection