@extends('layouts.app')
@section('content')
<h2>Edit Kunjungan</h2>
<p class="muted">Stok obat lama dikembalikan lalu dipotong ulang sesuai isian di bawah.</p>
@include('admin.visits._form', ['action' => route('admin.visits.update', $visit), 'method' => 'PUT'])
@endsection