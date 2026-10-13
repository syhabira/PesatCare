@extends('layouts.app')
@section('content')
<h2>Kelola Obat</h2>

<details class="card" open>
    <summary>Tambah obat baru</summary>
    <form method="POST" action="{{ route('admin.medicines.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <label>Nama<input name="name" required></label>
            <label>Golongan<input name="medicine_class" placeholder="Obat bebas, dll"></label>
            <label>Satuan<input name="unit" value="tablet" required style="width:90px"></label>
            <label>Batas stok menipis<input type="number" name="low_stock_threshold" value="10" min="0" required style="width:90px"></label>
            <label>Logo golongan<input type="file" name="symbol" accept="image/*"></label>
        </div>
        <label>Indikasi<br><input name="indication" style="width:100%"></label>
        <p class="muted">Stok awal (opsional, isi ketiganya):</p>
        <div class="row">
            <label>No. batch<input name="batch_number"></label>
            <label>Jumlah<input type="number" name="quantity" min="1" style="width:90px"></label>
            <label>Kedaluwarsa<input type="date" name="expiry_date"></label>
            <button class="btn">Tambah obat</button>
        </div>
    </form>
</details>

<form method="GET" class="row">
    <input name="q" value="{{ request('q') }}" placeholder="Cari obat">
    <label style="display:inline"><input type="checkbox" name="filter" value="low" @checked(request('filter') === 'low')> Stok menipis saja</label>
    <button class="btn">Cari</button>
</form>

<table>
    <tr><th>Obat</th><th>Golongan</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
    @forelse($medicines as $m)
    <tr>
        <td>
            @if($m->symbol)<img src="{{ asset('storage/' . $m->symbol) }}" alt="" height="24" style="vertical-align:middle">@endif
            <a href="{{ route('admin.medicines.show', $m) }}">{{ $m->name }}</a><br><span class="muted">{{ $m->indication }}</span>
        </td>
        <td>{{ $m->medicine_class }}</td>
        <td>{{ (int) $m->stock }} {{ $m->unit }} @if($m->isLowStock())<b style="color:#b45309">Stok menipis</b>@endif</td>
        <td>{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        <td>
            <details><summary>Edit</summary>
                <form method="POST" action="{{ route('admin.medicines.update', $m) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $m->name }}" required>
                    <input name="medicine_class" value="{{ $m->medicine_class }}" placeholder="Golongan">
                    <input name="unit" value="{{ $m->unit }}" required style="width:80px">
                    <input type="number" name="low_stock_threshold" value="{{ $m->low_stock_threshold }}" min="0" required style="width:80px">
                    <input name="indication" value="{{ $m->indication }}" placeholder="Indikasi">
                    <input type="file" name="symbol" accept="image/*">
                    <button class="btn">Simpan</button>
                </form>
            </details>
            <form method="POST" action="{{ route('admin.medicines.toggle', $m) }}">@csrf @method('PATCH') <button class="btn">{{ $m->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada obat.</td></tr>
    @endforelse
</table>
{{ $medicines->links() }}
@endsection