<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PesatCare</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:system-ui,sans-serif;margin:0;color:#14213d;background:#f6f9fb}
body nav{background:#3368A0;padding:8px 16px;display:flex;flex-wrap:wrap;gap:12px;align-items:center}
body nav a,body nav button{color:#fff;background:none;border:0;cursor:pointer;font-size:14px}
body main{padding:16px;max-width:1100px;margin:auto}
body table{width:100%;border-collapse:collapse;margin:8px 0;background:#fff;font-size:14px}
body th,body td{border:1px solid #ccd;padding:4px 8px;text-align:left;vertical-align:top}
body th{background:#C8DFDB}
body input,body select,body textarea{border:1px solid #99a;padding:4px;border-radius:3px;max-width:100%;background:#fff}
body .btn{background:#3368A0;color:#fff;border:0;padding:5px 12px;border-radius:3px;cursor:pointer;text-decoration:none;display:inline-block;font-size:14px}
body .btn-red{background:#b91c1c}
body .card{background:#fff;border:1px solid #ccd;padding:12px;margin:8px 0;border-radius:4px}
body .row{display:flex;flex-wrap:wrap;gap:8px;align-items:flex-end}
body .ok{background:#d1fae5;padding:8px;margin-bottom:8px;border-radius:3px}
body .bad{background:#fee2e2;padding:8px;margin-bottom:8px;border-radius:3px}
body label{display:block;font-size:13px}
body .muted{color:#667}
body details{margin:4px 0}
body summary{cursor:pointer;color:#3368A0}
</style>
</head>
<body>
@auth
@php
    $role = auth()->user()->role;
    $menus = [
        'admin' => [
            'admin.dashboard' => 'Dashboard', 'admin.visits.index' => 'Kunjungan', 'admin.diseases.index' => 'Penyakit',
            'admin.medicines.index' => 'Obat', 'admin.screenings.index' => 'Screening', 'admin.teacher-checks.index' => 'Pemeriksaan Guru',
            'admin.certificates.index' => 'Surat', 'admin.reports' => 'Rekap', 'admin.triage-rules.index' => 'Aturan Triase',
            'admin.classes.index' => 'Kelas', 'admin.units.index' => 'Unit', 'admin.students.index' => 'Siswa',
            'admin.teachers.index' => 'Guru', 'admin.users.index' => 'Akun',
        ],
        'guru' => ['guru.dashboard' => 'Dashboard', 'guru.profile' => 'Profil', 'guru.health-checks' => 'Pemeriksaan', 'guru.visits' => 'Kunjungan'],
        'murid' => ['murid.dashboard' => 'Dashboard', 'murid.profile' => 'Profil', 'murid.visits' => 'Kunjungan', 'murid.screenings' => 'Screening', 'murid.certificates' => 'Surat'],
    ];
@endphp
<nav>
    <b style="color:#fff">PesatCare</b>
    @foreach($menus[$role] as $r => $label)
        <a href="{{ route($r) }}">{{ $label }}</a>
    @endforeach
    <span style="margin-left:auto;color:#C8DFDB;font-size:13px">{{ auth()->user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">@csrf <button>Logout</button></form>
</nav>
@endauth

<main>
    @if(session('success')) <div class="ok">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="bad"><ul style="margin:0;padding-left:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
</body>
</html>