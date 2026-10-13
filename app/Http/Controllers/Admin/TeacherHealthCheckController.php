<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherHealthCheck;
use Illuminate\Http\Request;

class TeacherHealthCheckController extends Controller
{
    public function index(Request $request)
    {
        $checks = TeacherHealthCheck::with(['teacher.unit', 'recorder:id,name'])
            ->when($request->q, fn ($q, $s) => $q->whereHas('teacher', fn ($t) => $t->where('name', 'like', "%$s%")->orWhere('nip', 'like', "%$s%")))
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('checked_at', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('checked_at', '<=', $v))
            ->latest('checked_at')->paginate(20)->withQueryString();

        return view('admin.teacher-checks.index', compact('checks'));
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->rules());
        TeacherHealthCheck::create($d + ['recorded_by' => $request->user()->id]);

        return back()->with('success', 'Pemeriksaan guru disimpan.');
    }

    public function update(Request $request, TeacherHealthCheck $teacherCheck)
    {
        $d = $request->validate(collect($this->rules())->except('teacher_id')->all());
        $teacherCheck->update($d + ['updated_by' => $request->user()->id]);

        return back()->with('success', 'Pemeriksaan guru diperbarui.');
    }

    public function history(Teacher $teacher)
    {
        $teacher->load('unit');
        $checks = $teacher->healthChecks()->latest('checked_at')->paginate(20);

        return view('admin.teacher-checks.history', compact('teacher', 'checks'));
    }

    private function rules(): array
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'checked_at' => 'required|date|before_or_equal:today',
            'uric_acid' => 'nullable|numeric|between:0.5,30',
            'cholesterol' => 'nullable|integer|between:50,700',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}