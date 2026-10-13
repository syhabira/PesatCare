<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiseaseController extends Controller
{
    public function index(Request $request)
    {
        $diseases = Disease::withCount('visits')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%"))
            ->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->boolean('active')))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.diseases.index', compact('diseases'));
    }

    public function store(Request $request)
    {
        Disease::create($request->validate([
            'name' => 'required|string|max:100|unique:diseases,name',
            'description' => 'nullable|string|max:500',
        ]));

        return back()->with('success', 'Penyakit ditambahkan.');
    }

    public function update(Request $request, Disease $disease)
    {
        $disease->update($request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('diseases', 'name')->ignore($disease->id)],
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]));

        return back()->with('success', 'Penyakit diperbarui.');
    }

    /** Tidak ada hapus permanen; data yang punya riwayat cukup dinonaktifkan. */
    public function toggle(Disease $disease)
    {
        $disease->update(['is_active' => ! $disease->is_active]);
        return back()->with('success', 'Status penyakit diubah.');
    }
}