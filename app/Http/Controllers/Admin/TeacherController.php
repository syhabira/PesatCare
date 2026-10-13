<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with('unit:id,unit_name')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('nip', 'like', "%$s%")))
            ->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.teachers.index', ['teachers' => $teachers, 'units' => Unit::orderBy('unit_name')->get(['id', 'unit_name'])]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'nip' => 'required|string|max:30|unique:teachers,nip',
            'name' => 'required|string|max:150',
            'unit_id' => 'nullable|exists:units,id',
            'lp_number' => 'nullable|string|max:50',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'username' => 'nullable|string|max:50|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($d) {
            $user = User::create([
                'name' => $d['name'], 'username' => $d['username'] ?? $d['nip'],
                'password' => $d['password'], 'role' => User::ROLE_GURU,
            ]);
            Teacher::create(collect($d)->only(['nip', 'name', 'unit_id', 'lp_number', 'blood_type'])->all() + ['user_id' => $user->id]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru & akun dibuat.');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $d = $request->validate([
            'nip' => ['required', 'string', 'max:30', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'name' => 'required|string|max:150',
            'unit_id' => 'nullable|exists:units,id',
            'lp_number' => 'nullable|string|max:50',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($teacher, $d) {
            $teacher->update($d);
            $teacher->user?->update(['name' => $d['name'], 'status' => $d['is_active'] ? 'active' : 'inactive']);
        });

        return back()->with('success', 'Data guru diperbarui.');
    }
}