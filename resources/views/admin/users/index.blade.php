@extends('layouts.app')
@section('content')
<h2>Akun</h2>
<details class="card"><summary>Tambah akun admin/petugas (akun guru & murid dibuat dari menu Guru/Siswa)</summary>
    <form method="POST" action="{{ route('admin.users.store') }}" class="row">
        @csrf
        <label>Nama<input name="name" required></label>
        <label>Username<input name="username" required></label>
        <label>Password (min 10)<input type="password" name="password" required></label>
        <button class="btn">Tambah</button>
    </form>
</details>

<form method="GET" class="row">
    <input name="q" value="{{ request('q') }}" placeholder="Nama / username">
    <select name="role"><option value="">Semua role</option>@foreach(['admin', 'guru', 'murid'] as $r)<option @selected(request('role') === $r)>{{ $r }}</option>@endforeach</select>
    <button class="btn">Cari</button>
</form>

<table>
    <tr><th>Nama</th><th>Username</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
    @forelse($users as $u)
    <tr>
        <td>{{ $u->name }}</td><td>{{ $u->username }}</td><td>{{ $u->role }}</td><td>{{ $u->status === 'active' ? 'Aktif' : 'Nonaktif' }}</td>
        <td>
            <details><summary>Reset password</summary>
                <form method="POST" action="{{ route('admin.users.password', $u) }}">
                    @csrf @method('PATCH')
                    <input type="password" name="password" placeholder="Password baru (min 8)" required>
                    <button class="btn">Simpan</button>
                </form>
            </details>
            @if($u->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.toggle', $u) }}">@csrf @method('PATCH') <button class="btn">{{ $u->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="muted">Belum ada akun.</td></tr>
    @endforelse
</table>
{{ $users->links() }}
@endsection