<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScreeningRequest;
use App\Models\SchoolClass;
use App\Models\Screening;
use App\Models\Student;
use App\Services\ScreeningService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    public function __construct(private ScreeningService $service) {}

    public function index(Request $request)
    {
        $request->validate(['date_from' => 'nullable|date', 'date_to' => 'nullable|date|after_or_equal:date_from']);

        $screenings = Screening::with(['student:id,nis,name', 'schoolClass:id,class_name', 'recorder:id,name'])
            ->when($request->q, fn ($q, $s) => $q->whereHas('student', fn ($x) => $x->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%")))
            ->when($request->class_id, fn ($q, $v) => $q->where('class_id', $v))
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('screened_at', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('screened_at', '<=', $v))
            ->latest('screened_at')->latest('id')->paginate(20)->withQueryString();

        return view('admin.screenings.index', [
            'screenings' => $screenings,
            'classes' => SchoolClass::orderBy('class_name')->get(['id', 'class_name']),
        ]);
    }

    public function create()
    {
        return view('admin.screenings.create');
    }

    public function store(ScreeningRequest $request)
    {
        $student = Student::findOrFail($request->student_id);
        $data = $this->service->compute($student, $request->validated());
        $data['recorded_by'] = $request->user()->id;

        $screening = Screening::create($data);

        return redirect()->route('admin.screenings.show', $screening)->with('success', 'Screening disimpan.');
    }

    public function show(Screening $screening)
    {
        $screening->load(['student.schoolClass', 'recorder']);
        return view('admin.screenings.show', compact('screening'));
    }

    public function edit(Screening $screening)
    {
        $screening->load('student');
        return view('admin.screenings.edit', compact('screening'));
    }

    public function update(ScreeningRequest $request, Screening $screening)
    {
        $student = Student::findOrFail($screening->student_id); // siswa tidak boleh diganti saat edit
        $data = $this->service->compute($student, $request->safe()->except('student_id') + ['student_id' => $student->id]);
        $data['updated_by'] = $request->user()->id;

        $screening->update($data);

        return redirect()->route('admin.screenings.show', $screening)->with('success', 'Screening diperbarui.');
    }

    public function pdf(Screening $screening)
    {
        $screening->load(['student.schoolClass', 'recorder']);

        return Pdf::loadView('pdf.screening', compact('screening'))
            ->setPaper('a4')->download('screening-' . $screening->student->nis . '-' . $screening->screened_at->format('Ymd') . '.pdf');
    }
}