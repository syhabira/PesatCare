@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto pb-6">

    <!-- ========================================================= -->
    <!-- HEADER SECTION: GREETING & ROLE INFO                      -->
    <!-- ========================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#2C5EAD] to-[#1591DC] text-white font-extrabold text-lg flex items-center justify-center shadow-md shadow-[#2C5EAD]/20 ring-4 ring-white/60">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    Hi, {{ strtok($teacher->name, ' ') }}
                </h1>
                <p class="text-xs text-slate-500 font-medium">
                    Dashboard Guru & Layanan Kesehatan UKS PesatCare
                </p>
            </div>
        </div>

        <!-- Quick Status Pill -->
        <div class="flex items-center gap-2 bg-white/70 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/80 shadow-sm text-xs font-bold text-slate-700 self-start sm:self-auto">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Status: Guru / Pengajar</span>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- STATS ROW (4 CARDS)                                       -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: NIP -->
        <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-4 border border-white/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">NIP / Identitas</p>
                <p class="text-sm font-extrabold text-slate-900 mt-1 truncate max-w-[150px]">{{ $teacher->nip ?? '-' }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#2C5EAD]/10 text-[#2C5EAD] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Unit / Bidang -->
        <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-4 border border-white/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit / Bidang</p>
                <p class="text-sm font-extrabold text-slate-900 mt-1 truncate max-w-[150px]">{{ $teacher->unit?->unit_name ?? '-' }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#1591DC]/10 text-[#1591DC] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <!-- Card 3: Golongan Darah -->
        <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-4 border border-white/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Golongan Darah</p>
                <p class="text-sm font-extrabold text-slate-900 mt-1">{{ $teacher->blood_type ?? '-' }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 4: Kunjungan Pribadi -->
        <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-4 border border-white/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kunjungan UKS Saya</p>
                <p class="text-sm font-extrabold text-slate-900 mt-1">{{ $visitCount }} <span class="text-xs font-normal text-slate-500">Kali</span></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- ========================================================= -->
    <!-- BANNER MONITORING SISWA (GURU PIKET)                      -->
    <!-- ========================================================= -->
    <div class="bg-gradient-to-r from-[#2C5EAD] to-[#1591DC] rounded-[28px] p-6 text-white shadow-lg shadow-[#2C5EAD]/20 flex flex-col md:flex-row md:items-center justify-between gap-5 relative overflow-hidden">
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-white">Fitur Guru Piket: Pantau Siswa Sakit</h2>
                <p class="text-xs text-white/80 mt-0.5">
                    Hari ini: <strong class="text-white">{{ $todayStudentVisitsCount }} siswa</strong> mengunjungi UKS (<strong class="text-amber-200">{{ $activeInUksCount }} masih dirawat</strong>).
                </p>
            </div>
        </div>
        <div class="relative z-10">
            <a href="{{ route('guru.monitoring') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-[#2C5EAD] hover:bg-white/90 text-xs font-bold shadow-md transition-all">
                <span>Buka Pemantauan</span>
                <span>→</span>
            </a>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- PEMERIKSAAN KESEHATAN TERAKHIR                            -->
    <!-- ========================================================= -->
    <div class="space-y-3">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700 px-1">Pemeriksaan Kesehatan Terakhir</h2>

        @if($lastCheck)
            <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-5 border border-white/80 shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal Pemeriksaan</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $lastCheck->checked_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Asam Urat</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $lastCheck->uric_acid ? $lastCheck->uric_acid . ' mg/dL' : '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kolesterol</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $lastCheck->cholesterol ? $lastCheck->cholesterol . ' mg/dL' : '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Golongan Darah</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $teacher->blood_type ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Catatan Medis</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5 truncate">{{ $lastCheck->notes ?: '-' }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-5 border border-white/80 shadow-sm text-center">
                <p class="text-xs font-medium text-slate-500">Belum ada data pemeriksaan kesehatan guru yang tercatat.</p>
            </div>
        @endif
    </div>

    <!-- ========================================================= -->
    <!-- RIWAYAT PEMERIKSAAN (TABEL)                               -->
    <!-- ========================================================= -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">Riwayat Pemeriksaan</h2>
            <a href="{{ route('guru.health-checks') }}" class="text-xs font-bold text-[#1591DC] hover:text-[#2C5EAD] transition">Lihat Semua →</a>
        </div>

        <div class="bg-white/60 backdrop-blur-xl rounded-2xl border border-white/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200/60 bg-white/40 text-slate-500 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 font-bold">Tanggal</th>
                            <th class="py-3 px-4 font-bold">Asam Urat (mg/dL)</th>
                            <th class="py-3 px-4 font-bold">Kolesterol (mg/dL)</th>
                            <th class="py-3 px-4 font-bold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($checks as $c)
                            <tr class="hover:bg-white/50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $c->checked_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ $c->uric_acid ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ $c->cholesterol ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $c->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-400 font-medium">Belum ada riwayat data pemeriksaan kesehatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection