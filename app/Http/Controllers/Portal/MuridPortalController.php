<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\Screening;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;

class MuridPortalController extends Controller
{
    private function student()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $s = $user?->student;
        abort_if(! $s, 403, 'Akun belum terhubung dengan profil siswa.');
        return $s->load('schoolClass');
    }

    public function dashboard()
    {
        $s = $this->student();

        return view('murid.dashboard', [
            'student' => $s,
            'latestScreening' => $s->screenings()->latest('screened_at')->latest('id')->first(),
            'visitCount' => $s->visits()->count(),
        ]);
    }

    public function profile()
    {
        $s = $this->student();
        return view('murid.profile', ['student' => $s, 'latestScreening' => $s->screenings()->latest('screened_at')->latest('id')->first()]);
    }

    public function visits()
    {
        $visits = Visit::where('student_id', $this->student()->id)
            ->with('diseases:id,name')
            ->select('id', 'student_id', 'visited_at', 'complaint', 'action')
            ->latest('visited_at')->paginate(15);

        return view('murid.visits', compact('visits'));
    }

    public function screenings()
    {
        $screenings = Screening::where('student_id', $this->student()->id)->latest('screened_at')->paginate(15);
        return view('murid.screenings', compact('screenings'));
    }

    public function screeningPdf(Screening $screening)
    {
        $this->authorize('download', $screening);
        $screening->load(['student.schoolClass', 'recorder']);

        return Pdf::loadView('pdf.screening', compact('screening'))->setPaper('a4')
            ->download('screening-' . $screening->screened_at->format('Ymd') . '.pdf');
    }

    public function certificates()
    {
        $certs = MedicalCertificate::whereHas('visit', fn ($q) => $q->where('student_id', $this->student()->id))
            ->with('visit:id,visited_at,complaint')->latest()->paginate(15);

        return view('murid.certificates', compact('certs'));
    }

    public function certificate(MedicalCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        $certificate->load(['visit.student.schoolClass', 'visit.diseases']);

        return view('murid.certificate-show', compact('certificate'));
    }

    public function certificatePdf(MedicalCertificate $certificate)
    {
        $this->authorize('download', $certificate);
        $certificate->load(['visit.student.schoolClass', 'visit.teacher.unit', 'visit.diseases', 'creator']);

        return Pdf::loadView('pdf.certificate', compact('certificate'))->setPaper('a4')->download('surat-keterangan.pdf');
    }
}