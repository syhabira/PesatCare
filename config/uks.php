<?php

// null = belum ditetapkan pihak kompeten -> sistem menampilkan "Belum ada acuan"
$num = fn (string $key) => ($v = env($key)) === null || $v === '' ? null : (float) $v;

return [
    'expiry_warning_days' => (int) env('UKS_EXPIRY_WARNING_DAYS', 30),
    'student_can_download_pdf' => (bool) env('UKS_STUDENT_PDF_DOWNLOAD', false),

    // true = hanya aturan triase yang sudah disahkan (is_validated) yang dipakai. Set true saat data nyata.
    'triage_require_validated' => (bool) env('UKS_TRIAGE_REQUIRE_VALIDATED', false),

    'waist_max_cm' => ['L' => $num('UKS_WAIST_MAX_L'), 'P' => $num('UKS_WAIST_MAX_P')],

    'blood_pressure' => [
        'systolic_high' => $num('UKS_BP_SYSTOLIC_HIGH'),
        'diastolic_high' => $num('UKS_BP_DIASTOLIC_HIGH'),
        'systolic_low' => $num('UKS_BP_SYSTOLIC_LOW'),
        'diastolic_low' => $num('UKS_BP_DIASTOLIC_LOW'),
    ],

    'school' => [
        'name' => env('UKS_SCHOOL_NAME', 'SMK Informatika Pesat Kota Bogor'),
        'address' => env('UKS_SCHOOL_ADDRESS'),
        'signer_name' => env('UKS_SIGNER_NAME'),
        'signer_title' => env('UKS_SIGNER_TITLE', 'Petugas UKS'),
    ],
];