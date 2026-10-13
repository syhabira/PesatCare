<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->role, fn ($q, $r) => $q->where('role', $r))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('username', 'like', "%$s%")))
            ->orderBy('role')->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /** Hanya untuk akun petugas/admin baru. Akun guru & murid dibuat lewat modul Guru/Siswa. */
    public function store(Request $request)
    {
        $d = $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:10',
        ]);
        User::create($d + ['role' => User::ROLE_ADMIN]);

        return back()->with('success', 'Akun admin dibuat.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $user->update($request->validate(['password' => 'required|string|min:8']));
        return back()->with('success', 'Password direset.');
    }

    public function toggle(Request $request, User $user)
    {
        abort_if($user->id === $request->user()->id, 422, 'Tidak dapat menonaktifkan akun sendiri.');
        $user->update(['status' => $user->isActive() ? 'inactive' : 'active']);

        return back()->with('success', 'Status akun diubah.');
    }
}