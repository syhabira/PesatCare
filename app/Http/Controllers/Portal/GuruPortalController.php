<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Visit;

class GuruPortalController extends Controller
{
    private function teacher()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $t = $user?->teacher;
        abort_if(! $t, 403, 'Akun belum terhubung dengan profil guru.');
        return $t->load('unit');
    }

    public function dashboard()
    {
        $t = $this->teacher();

        return view('guru.dashboard', [
            'teacher' => $t,
            'lastCheck' => $t->healthChecks()->latest('checked_at')->first(),
            'checks' => $t->healthChecks()->latest('checked_at')->limit(5)->get(),
            'visitCount' => $t->visits()->count(),
        ]);
    }

    public function profile()
    {
        return view('guru.profile', ['teacher' => $this->teacher()]);
    }

    public function healthChecks()
    {
        $checks = $this->teacher()->healthChecks()
            ->select('id', 'teacher_id', 'checked_at', 'uric_acid', 'cholesterol', 'notes')
            ->latest('checked_at')->paginate(15);

        return view('guru.health-checks', ['checks' => $checks, 'teacher' => $this->teacher()]);
    }

    public function visits()
    {
        $visits = Visit::where('teacher_id', $this->teacher()->id)
            ->with('diseases:id,name')
            ->select('id', 'teacher_id', 'visited_at', 'complaint', 'action')
            ->latest('visited_at')->paginate(15);

        return view('guru.visits', compact('visits'));
    }
}