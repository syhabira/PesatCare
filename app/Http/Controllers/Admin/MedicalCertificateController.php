<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MedicalCertificateController extends Controller
{
    public function index(Request $request)
    {
        $certs = MedicalCertificate::with(['visit.student:id,name', 'visit.teacher:id,name', 'creator:id,name'])
            ->when($request->q, fn ($q, $s) => $q->whereHas('visit', fn ($v) => $v
                ->whereHas('student', fn ($x) => $x->where('name', 'like', "%$s%"))
                ->orWhereHas('teacher', fn ($x) => $x->where('name', 'like', "%$s%"))))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.certificates.index', compact('certs'));
    }

    /** Buat surat dari kunjungan; identitas & tanda vital diambil dari visit (tidak diinput ulang). */
    public function store(Request $request, Visit $visit)
    {
        if ($visit->certificate) {
            return redirect()->route('admin.certificates.show', $visit->certificate)
                ->with('success', 'Surat untuk kunjungan ini sudah ada.');
        }

        $d = $request->validate([
            'status_home' => 'required|boolean',
            'departure_time' => 'nullable|date|required_if:status_home,1',
            'additional_notes' => 'nullable|string|max:1000',
            'letter_number' => 'nullable|string|max:100',
        ]);

        $cert = $visit->certificate()->create($d + ['created_by' => $request->user()->id]);

        return redirect()->route('admin.certificates.show', $cert)->with('success', 'Surat keterangan dibuat.');
    }

    public function show(MedicalCertificate $certificate)
    {
        $certificate->load(['visit.student.schoolClass', 'visit.teacher.unit', 'visit.diseases', 'creator']);
        return view('admin.certificates.show', compact('certificate'));
    }

    public function update(Request $request, MedicalCertificate $certificate)
    {
        $d = $request->validate([
            'status_home' => 'required|boolean',
            'departure_time' => 'nullable|date|required_if:status_home,1',
            'additional_notes' => 'nullable|string|max:1000',
            'letter_number' => 'nullable|string|max:100',
        ]);
        $certificate->update($d);

        return back()->with('success', 'Surat diperbarui.');
    }

    public function pdf(MedicalCertificate $certificate)
    {
        $certificate->load(['visit.student.schoolClass', 'visit.teacher.unit', 'visit.diseases', 'creator']);

        return Pdf::loadView('pdf.certificate', compact('certificate'))
            ->setPaper('a4')->download('surat-keterangan-' . $certificate->id . '.pdf');
    }
}