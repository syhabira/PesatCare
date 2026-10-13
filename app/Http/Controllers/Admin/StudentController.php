<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('schoolClass:id,class_name')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%")))
            ->when($request->class_id, fn ($q, $v) => $q->where('class_id', $v))
            ->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'classes' => SchoolClass::orderBy('class_name')->get(['id', 'class_name']),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'nis' => 'required|string|max:30|unique:students,nis',
            'name' => 'required|string|max:150',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date|before:today',
            'username' => 'nullable|string|max:50|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($d) {
            $user = User::create([
                'name' => $d['name'], 'username' => $d['username'] ?? $d['nis'],
                'password' => $d['password'], 'role' => User::ROLE_MURID,
            ]);
            Student::create(collect($d)->only(['nis', 'name', 'class_id', 'gender', 'birth_date'])->all() + ['user_id' => $user->id]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa & akun dibuat.');
    }

    public function update(Request $request, Student $student)
    {
        $d = $request->validate([
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($student->id)],
            'name' => 'required|string|max:150',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date|before:today',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($student, $d) {
            $student->update($d);
            $student->user?->update(['name' => $d['name'], 'status' => $d['is_active'] ? 'active' : 'inactive']);
        });

        return back()->with('success', 'Data siswa diperbarui.');
    }
}