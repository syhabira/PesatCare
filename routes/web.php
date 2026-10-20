<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Portal\GuruPortalController;
use App\Http\Controllers\Portal\MuridPortalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', function (Request $request) {
    if ($request->user()) {
        return redirect()->route($request->user()->homeRoute());
    }

    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', function (Request $request) {
    return redirect()->route($request->user()->homeRoute());
})->middleware('auth')->name('dashboard');

/* ======================= ADMIN ======================= */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');

    // Kunjungan (+ triase)
    Route::get('visits/triage-preview', [Admin\VisitController::class, 'triagePreview'])->name('visits.triage-preview');
    Route::get('visits/patients', [Admin\VisitController::class, 'searchPatients'])->name('visits.patients');
    Route::resource('visits', Admin\VisitController::class);
    Route::patch('visits/{visit}/triage-handled', [Admin\VisitController::class, 'handleTriage'])->name('visits.triage-handled');
    Route::post('visits/{visit}/certificate', [Admin\MedicalCertificateController::class, 'store'])->name('visits.certificate.store');

    // Penyakit
    Route::resource('diseases', Admin\DiseaseController::class)->only(['index', 'store', 'update']);
    Route::patch('diseases/{disease}/toggle', [Admin\DiseaseController::class, 'toggle'])->name('diseases.toggle');

    // Obat
    Route::resource('medicines', Admin\MedicineController::class)->only(['index', 'show', 'store', 'update']);
    Route::patch('medicines/{medicine}/toggle', [Admin\MedicineController::class, 'toggle'])->name('medicines.toggle');
    Route::post('medicines/{medicine}/stock', [Admin\MedicineController::class, 'addStock'])->name('medicines.stock.add');
    Route::patch('batches/{batch}/adjust', [Admin\MedicineController::class, 'adjustStock'])->name('batches.adjust');

    // Screening
    Route::resource('screenings', Admin\ScreeningController::class)->except(['destroy']);
    Route::get('screenings/{screening}/pdf', [Admin\ScreeningController::class, 'pdf'])->name('screenings.pdf');

    // Pemeriksaan guru
    Route::get('teacher-checks', [Admin\TeacherHealthCheckController::class, 'index'])->name('teacher-checks.index');
    Route::post('teacher-checks', [Admin\TeacherHealthCheckController::class, 'store'])->name('teacher-checks.store');
    Route::put('teacher-checks/{teacherCheck}', [Admin\TeacherHealthCheckController::class, 'update'])->name('teacher-checks.update');
    Route::get('teachers/{teacher}/checks', [Admin\TeacherHealthCheckController::class, 'history'])->name('teacher-checks.history');

    // Surat keterangan
    Route::get('certificates', [Admin\MedicalCertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [Admin\MedicalCertificateController::class, 'show'])->name('certificates.show');
    Route::put('certificates/{certificate}', [Admin\MedicalCertificateController::class, 'update'])->name('certificates.update');
    Route::get('certificates/{certificate}/pdf', [Admin\MedicalCertificateController::class, 'pdf'])->name('certificates.pdf');

    // Rekap
    Route::get('reports', Admin\ReportController::class)->name('reports');

    // Aturan triase
    Route::get('triage-rules', [Admin\TriageRuleController::class, 'index'])->name('triage-rules.index');
    Route::post('triage-rules', [Admin\TriageRuleController::class, 'store'])->name('triage-rules.store');
    Route::put('triage-rules/{rule}', [Admin\TriageRuleController::class, 'update'])->name('triage-rules.update');
    Route::patch('triage-rules/{rule}/toggle', [Admin\TriageRuleController::class, 'toggle'])->name('triage-rules.toggle');
    Route::patch('triage-rules/{rule}/validate', [Admin\TriageRuleController::class, 'validateRule'])->name('triage-rules.validate');

    // Master data & akun
    Route::get('classes', [Admin\MasterDataController::class, 'classes'])->name('classes.index');
    Route::post('classes', [Admin\MasterDataController::class, 'storeClass'])->name('classes.store');
    Route::put('classes/{class}', [Admin\MasterDataController::class, 'updateClass'])->name('classes.update');
    Route::get('units', [Admin\MasterDataController::class, 'units'])->name('units.index');
    Route::post('units', [Admin\MasterDataController::class, 'storeUnit'])->name('units.store');
    Route::put('units/{unit}', [Admin\MasterDataController::class, 'updateUnit'])->name('units.update');

    Route::resource('students', Admin\StudentController::class)->only(['index', 'store', 'update']);
    Route::resource('teachers', Admin\TeacherController::class)->only(['index', 'store', 'update']);

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::patch('users/{user}/password', [Admin\UserController::class, 'resetPassword'])->name('users.password');
    Route::patch('users/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');
});

/* ======================= GURU ======================= */
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [GuruPortalController::class, 'profile'])->name('profile');
    Route::get('/pemeriksaan', [GuruPortalController::class, 'healthChecks'])->name('health-checks');
    Route::get('/kunjungan', [GuruPortalController::class, 'visits'])->name('visits');
});

/* ======================= MURID ======================= */
Route::middleware(['auth', 'role:murid'])->prefix('murid')->name('murid.')->group(function () {
    Route::get('/dashboard', [MuridPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [MuridPortalController::class, 'profile'])->name('profile');
    Route::get('/kunjungan', [MuridPortalController::class, 'visits'])->name('visits');
    Route::get('/screening', [MuridPortalController::class, 'screenings'])->name('screenings');
    Route::get('/screening/{screening}/pdf', [MuridPortalController::class, 'screeningPdf'])->name('screenings.pdf');
    Route::get('/surat', [MuridPortalController::class, 'certificates'])->name('certificates');
    Route::get('/surat/{certificate}', [MuridPortalController::class, 'certificate'])->name('certificates.show');
    Route::get('/surat/{certificate}/pdf', [MuridPortalController::class, 'certificatePdf'])->name('certificates.pdf');
});
