<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuruPortalController extends Controller
{
    private function teacher(): Teacher
    {
        $user = Auth::user();
        $t = $user?->teacher;
        abort_if(! $t, 403, 'Akun belum terhubung dengan profil guru.');

        return $t->load('unit');
    }

    public function dashboard(): View
    {
        $t = $this->teacher();

        // Kunjungan siswa hari ini untuk widget ringkasan
        $todayStudentVisitsCount = Visit::whereNotNull('student_id')
            ->whereDate('visited_at', today())
            ->count();

        // Siswa yang masih di UKS hari ini (belum ada waktu keluar/pulang)
        $activeInUksCount = Visit::whereNotNull('student_id')
            ->whereDate('visited_at', today())
            ->whereNull('left_at')
            ->whereDoesntHave('certificate', fn ($q) => $q->where('status_home', true))
            ->count();

        return view('guru.dashboard', [
            'teacher' => $t,
            'lastCheck' => $t->healthChecks()->latest('checked_at')->first(),
            'checks' => $t->healthChecks()->latest('checked_at')->limit(5)->get(),
            'visitCount' => $t->visits()->count(),
            'todayStudentVisitsCount' => $todayStudentVisitsCount,
            'activeInUksCount' => $activeInUksCount,
        ]);
    }

    /**
     * Fitur Guru Piket: Pantau siswa yang sedang atau pernah ke UKS hari ini.
     * Guru bisa memantau apakah siswa sudah boleh kembali ke kelas, dipulangkan, atau masih di UKS.
     */
    public function monitoring(Request $request): View
    {
        $teacher = $this->teacher();
        $date = $request->input('date', today()->toDateString());
        $classId = $request->input('class_id');
        $statusFilter = $request->input('status'); // 'di_uks', 'pulang', 'kembali_ke_kelas'

        $query = Visit::whereNotNull('student_id')
            ->whereDate('visited_at', $date)
            ->with([
                'student.schoolClass',
                'schoolClass',
                'diseases:id,name',
                'certificate',
                'recorder:id,name',
            ])
            ->when($classId, function ($q, $cid) {
                $q->where(function ($w) use ($cid) {
                    $w->where('class_id', $cid)
                        ->orWhereHas('student', fn ($s) => $s->where('class_id', $cid));
                });
            })
            ->when($statusFilter === 'pulang', function ($q) {
                $q->whereHas('certificate', fn ($c) => $c->where('status_home', true));
            })
            ->when($statusFilter === 'kembali_ke_kelas', function ($q) {
                $q->whereNotNull('left_at')
                    ->whereDoesntHave('certificate', fn ($c) => $c->where('status_home', true));
            })
            ->when($statusFilter === 'di_uks', function ($q) {
                $q->whereNull('left_at')
                    ->whereDoesntHave('certificate', fn ($c) => $c->where('status_home', true));
            })
            ->latest('visited_at');

        $visits = $query->paginate(20)->withQueryString();
        $classes = SchoolClass::orderBy('class_name')->get(['id', 'class_name']);

        // Hitung metrik ringkasan hari ini
        $baseToday = Visit::whereNotNull('student_id')->whereDate('visited_at', $date);
        $summary = [
            'total' => (clone $baseToday)->count(),
            'di_uks' => (clone $baseToday)->whereNull('left_at')
                ->whereDoesntHave('certificate', fn ($c) => $c->where('status_home', true))->count(),
            'pulang' => (clone $baseToday)->whereHas('certificate', fn ($c) => $c->where('status_home', true))->count(),
            'kembali' => (clone $baseToday)->whereNotNull('left_at')
                ->whereDoesntHave('certificate', fn ($c) => $c->where('status_home', true))->count(),
        ];

        return view('guru.monitoring', compact('visits', 'classes', 'teacher', 'date', 'classId', 'statusFilter', 'summary'));
    }

    public function profile(): View
    {
        return view('guru.profile', ['teacher' => $this->teacher()]);
    }

    public function healthChecks(): View
    {
        $checks = $this->teacher()->healthChecks()
            ->select('id', 'teacher_id', 'checked_at', 'uric_acid', 'cholesterol', 'notes')
            ->latest('checked_at')->paginate(15);

        return view('guru.health-checks', ['checks' => $checks, 'teacher' => $this->teacher()]);
    }

    public function visits(): View
    {
        $visits = Visit::where('teacher_id', $this->teacher()->id)
            ->with('diseases:id,name')
            ->select('id', 'teacher_id', 'visited_at', 'complaint', 'action')
            ->latest('visited_at')->paginate(15);

        return view('guru.visits', compact('visits'));
    }
}
