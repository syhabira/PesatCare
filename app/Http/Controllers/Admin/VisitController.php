<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisitRequest;
use App\Models\Disease;
use App\Models\Medicine;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Visit;
use App\Services\TriageService;
use App\Services\VisitService;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(private VisitService $visits) {}

    public function index(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date', 'date_to' => 'nullable|date|after_or_equal:date_from',
            'class_id' => 'nullable|integer', 'disease_id' => 'nullable|integer',
            'recorded_by' => 'nullable|integer', 'triage_level' => 'nullable|in:hijau,kuning,merah',
        ]);

        $q = Visit::with(['student:id,nis,name', 'teacher:id,nip,name', 'schoolClass:id,class_name',
            'unit:id,unit_name', 'recorder:id,name', 'diseases:id,name'])
            ->when($request->q, function ($q, $s) {
                $q->where(fn ($w) => $w
                    ->whereHas('student', fn ($x) => $x->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"))
                    ->orWhereHas('teacher', fn ($x) => $x->where('name', 'like', "%$s%")->orWhere('nip', 'like', "%$s%")));
            })
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('visited_at', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('visited_at', '<=', $v))
            ->when($request->class_id, fn ($q, $v) => $q->where('class_id', $v))
            ->when($request->disease_id, fn ($q, $v) => $q->whereHas('diseases', fn ($d) => $d->where('diseases.id', $v)))
            ->when($request->recorded_by, fn ($q, $v) => $q->where('recorded_by', $v))
            ->when($request->triage_level, fn ($q, $v) => $q->where('triage_level', $v))
            ->latest('visited_at');

        return view('admin.visits.index', [
            'visits' => $q->paginate(20)->withQueryString(),
            'classes' => SchoolClass::orderBy('class_name')->get(['id', 'class_name']),
            'diseases' => Disease::orderBy('name')->get(['id', 'name']),
            'officers' => User::where('role', 'admin')->get(['id', 'name']),
        ]);
    }

    public function create()
    {
        return view('admin.visits.create', $this->formData());
    }

    public function store(VisitRequest $request)
    {
        $visit = $this->visits->store($request->validated(), $request->user());

        return redirect()->route('admin.visits.show', $visit)->with('success', 'Kunjungan berhasil disimpan.');
    }

    public function show(Visit $visit)
    {
        $visit->load(['student.schoolClass', 'teacher.unit', 'schoolClass', 'unit', 'recorder', 'updater:id,name',
            'diseases', 'medicineUsages.batch.medicine', 'certificate']);

        return view('admin.visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $visit->load(['diseases', 'medicineUsages.batch']);
        return view('admin.visits.edit', ['visit' => $visit] + $this->formData());
    }

    public function update(VisitRequest $request, Visit $visit)
    {
        $this->visits->update($visit, $request->validated(), $request->user());

        return redirect()->route('admin.visits.show', $visit)->with('success', 'Kunjungan diperbarui.');
    }

    public function destroy(Request $request, Visit $visit)
    {
        $this->visits->destroy($visit, $request->user());

        return redirect()->route('admin.visits.index')->with('success', 'Kunjungan dihapus, stok obat dikembalikan.');
    }

    /** Pratinjau triase untuk panel hasil di form (dipanggil via fetch/AJAX). */
    public function triagePreview(Request $request, TriageService $triage)
    {
        $d = $request->validate([
            'temperature' => 'nullable|numeric|between:30,45',
            'systolic' => 'nullable|integer|between:40,300',
            'diastolic' => 'nullable|integer|between:20,200',
            'pulse' => 'nullable|integer|between:20,250',
            'disease_ids' => 'nullable|array', 'disease_ids.*' => 'integer',
        ]);

        $r = $triage->evaluate($d);
        return response()->json(['level' => $r['level'], 'reason' => $r['reason'], 'deadline_minutes' => $r['deadline_minutes']]);
    }

    public function handleTriage(Visit $visit)
    {
        $this->visits->markTriageHandled($visit);
        return back()->with('success', 'Triase ditandai sudah ditangani.');
    }

    /** Pencarian pasien untuk select di form kunjungan. */
    public function searchPatients(Request $request)
    {
        $d = $request->validate(['type' => 'required|in:murid,guru', 'q' => 'required|string|min:2|max:50']);
        $s = $d['q'];

        $rows = $d['type'] === 'murid'
            ? Student::active()->with('schoolClass:id,class_name')
                ->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"))
                ->limit(15)->get()->map(fn ($x) => ['id' => $x->id, 'name' => $x->name, 'code' => $x->nis, 'group' => $x->schoolClass?->class_name])
            : Teacher::active()->with('unit:id,unit_name')
                ->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('nip', 'like', "%$s%"))
                ->limit(15)->get()->map(fn ($x) => ['id' => $x->id, 'name' => $x->name, 'code' => $x->nip, 'group' => $x->unit?->unit_name]);

        return response()->json($rows);
    }

    private function formData(): array
    {
        return [
            'diseases' => Disease::active()->orderBy('name')->get(['id', 'name']),
            'medicines' => Medicine::active()->withStock()->orderBy('name')->get(),
        ];
    }
}