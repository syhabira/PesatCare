<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\TriageRule;
use Illuminate\Http\Request;

class TriageRuleController extends Controller
{
    public function index()
    {
        return view('admin.triage-rules.index', [
            'rules' => TriageRule::with('disease:id,name')->orderBy('level')->orderBy('parameter')->get(),
            'diseases' => Disease::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        TriageRule::create($this->validated($request) + ['is_validated' => false]);
        return back()->with('success', 'Aturan triase ditambahkan (belum disahkan).');
    }

    /** Mengubah aturan menaikkan versi dan mencabut status "disahkan". Kunjungan lama tetap memakai snapshot-nya. */
    public function update(Request $request, TriageRule $rule)
    {
        $rule->update($this->validated($request) + [
            'version' => $rule->version + 1,
            'is_validated' => false, 'validated_by' => null, 'validated_at' => null,
        ]);

        return back()->with('success', 'Aturan diperbarui. Perlu disahkan ulang.');
    }

    public function toggle(TriageRule $rule)
    {
        $rule->update(['is_active' => ! $rule->is_active]);
        return back()->with('success', 'Status aturan diubah.');
    }

    public function validateRule(Request $request, TriageRule $rule)
    {
        $rule->update(['is_validated' => true, 'validated_by' => $request->user()->id, 'validated_at' => now()]);
        return back()->with('success', 'Aturan ditandai disahkan.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:150',
            'parameter' => 'required|in:temperature,systolic,diastolic,pulse,disease',
            'operator' => 'required_unless:parameter,disease|nullable|in:gte,gt,lte,lt,between',
            'value' => 'required_unless:parameter,disease|nullable|numeric',
            'value_max' => 'required_if:operator,between|nullable|numeric|gte:value',
            'disease_id' => 'required_if:parameter,disease|nullable|exists:diseases,id',
            'level' => 'required|in:hijau,kuning,merah',
            'message' => 'nullable|string|max:255',
            'deadline_minutes' => 'nullable|integer|min:1|max:1440',
            'is_active' => 'sometimes|boolean',
        ]);
    }
}