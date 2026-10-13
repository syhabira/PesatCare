<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    public function classes()
    {
        return view('admin.classes.index', ['classes' => SchoolClass::withCount('students')->orderBy('class_name')->paginate(30)]);
    }

    public function storeClass(Request $request)
    {
        SchoolClass::create($request->validate([
            'class_name' => ['required', 'string', 'max:50',
                Rule::unique('classes', 'class_name')->where('academic_year', $request->academic_year)],
            'grade_level' => 'required|integer|between:1,13',
            'school_level' => 'required|in:SMP,SMA,SMK',
            'academic_year' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
        ]));

        return back()->with('success', 'Kelas ditambahkan.');
    }

    public function updateClass(Request $request, SchoolClass $class)
    {
        $class->update($request->validate([
            'class_name' => 'required|string|max:50',
            'grade_level' => 'required|integer|between:1,13',
            'school_level' => 'required|in:SMP,SMA,SMK',
            'academic_year' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'is_active' => 'required|boolean',
        ]));

        return back()->with('success', 'Kelas diperbarui.');
    }

    public function units()
    {
        return view('admin.units.index', ['units' => Unit::withCount('teachers')->orderBy('unit_name')->paginate(30)]);
    }

    public function storeUnit(Request $request)
    {
        Unit::create($request->validate(['unit_name' => 'required|string|max:100|unique:units,unit_name', 'description' => 'nullable|string|max:500']));
        return back()->with('success', 'Unit ditambahkan.');
    }

    public function updateUnit(Request $request, Unit $unit)
    {
        $unit->update($request->validate([
            'unit_name' => ['required', 'string', 'max:100', Rule::unique('units', 'unit_name')->ignore($unit->id)],
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]));

        return back()->with('success', 'Unit diperbarui.');
    }
}