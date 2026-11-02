@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto pb-6">

    <!-- ========================================================= -->
    <!-- HEADER SECTION: GREETING & STATUS                         -->
    <!-- ========================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#2C5EAD] to-[#1591DC] text-white font-extrabold text-lg flex items-center justify-center shadow-md shadow-[#2C5EAD]/20 ring-4 ring-white/60">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    Hi, {{ strtok($student->name, ' ') }}
                </h1>
                <p class="text-xs text-slate-500 font-medium">
                    Dashboard Kesehatan & Aktivitas UKS PesatCare
                </p>
            </div>
        </div>

        <!-- Quick Status Pill -->
        </div>

    <!-- ========================================================= -->
    <!-- MAIN GRID LAYOUT (ALA DASHBOARD LISSO)                    -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- ===================================================== -->
        <!-- LEFT COLUMN: STATS & BIG HEALTH SCORE CARD (Cols 1-4) -->
        <!-- ===================================================== -->
        <div class="lg:col-span-4 space-y-5">
            
            <!-- Health Score Card -->
            <div class="bg-white/60 backdrop-blur-xl rounded-[28px] p-6 border border-white/80 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-[#1591DC]/10 rounded-full blur-2xl group-hover:scale-125 transition-transform"></div>
                
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Indeks Kebugaran</span>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-5xl font-black text-slate-900 tracking-tight">94%</span>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">Optimal</span>
                </div>
                <p class="text-xs text-slate-500 mt-2">Berdasarkan rekam medis dan kunjungan UKS terakhir.</p>
                
                <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">Kelas</span>
                    <span class="px-3 py-1 rounded-xl bg-[#2C5EAD] text-white text-xs font-bold shadow-sm">
                        {{ $student->schoolClass?->class_name ?? '-' }}
                    </span>
                </div>
            </div>

            <!-- Activity Summary Widget (Senada dengan palet #2C5EAD & #C4E2F5) -->
            <div class="bg-gradient-to-br from-[#2C5EAD] to-[#1591DC] rounded-[28px] p-6 text-white shadow-lg shadow-[#2C5EAD]/20 relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-white/80">Ringkasan Medis</span>
                    <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] font-bold text-white">Live Data</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-3xl font-black text-white">{{ $visitCount }}</span>
                        <p class="text-xs font-medium text-white/80 mt-0.5">Kunjungan UKS</p>
                    </div>
                    <div>
                        <span class="text-3xl font-black text-white">{{ $latestScreening ? '1' : '0' }}</span>
                        <p class="text-xs font-medium text-white/80 mt-0.5">Screening Selesai</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ===================================================== -->
        <!-- MIDDLE COLUMN: SCREENING DETAILS CARD (Cols 5-9)      -->
        <!-- ===================================================== -->
        <div class="lg:col-span-5 space-y-5">
            
            <div class="bg-white/60 backdrop-blur-xl rounded-[28px] p-6 border border-white/80 shadow-sm space-y-5 h-full flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#2C5EAD]/10 text-[#2C5EAD] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-sm font-extrabold text-slate-900">Screening Kesehatan Terakhir</h2>
                    </div>
                    @if($latestScreening)
                        <span class="text-[11px] font-bold text-slate-600 bg-white/80 border border-slate-200 px-3 py-1 rounded-full shadow-xs">
                            {{ $latestScreening->screened_at->format('d M Y') }}
                        </span>
                    @endif
                </div>

                @if($latestScreening)
                    <div class="grid grid-cols-2 gap-3.5 my-auto">
                        <div class="p-4 rounded-2xl bg-white/80 border border-white shadow-xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tinggi / Berat</span>
                            <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $latestScreening->height_cm }} cm / {{ $latestScreening->weight_kg }} kg</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/80 border border-white shadow-xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">BMI & Status</span>
                            <p class="text-sm font-extrabold text-[#2C5EAD] mt-1">
                                {{ $latestScreening->bmi }} <span class="text-xs font-normal text-slate-500">({{ $latestScreening->weight_indicator ?? 'Normal' }})</span>
                            </p>
                        </div>
                        <div class="col-span-2 p-4 rounded-2xl bg-white/80 border border-white shadow-xs flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tekanan Darah</span>
                                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $latestScreening->blood_pressure ?? 'Normal' }}</p>
                            </div>
                            @if($latestScreening->blood_pressure_category)
                                <span class="px-3 py-1 rounded-xl bg-[#C4E2F5]/50 border border-[#1591DC]/20 text-[#2C5EAD] text-xs font-bold">
                                    {{ $latestScreening->blood_pressure_category }}
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center space-y-2 my-auto">
                        <div class="w-12 h-12 rounded-2xl bg-[#C4E2F5]/40 text-[#2C5EAD] mx-auto flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700">Belum ada data screening tercatat</p>
                        <p class="text-[11px] text-slate-400">Silakan kunjungi petugas UKS untuk melakukan pemeriksaan rutin.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- ===================================================== -->
        <!-- RIGHT COLUMN: QUICK ACCESS (Cols 10-12)               -->
        <!-- ===================================================== -->
        <div class="lg:col-span-3 space-y-5">
            
            <div class="bg-white/60 backdrop-blur-xl rounded-[28px] p-6 border border-white/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Akses Cepat</span>
                    <span class="w-2 h-2 rounded-full bg-[#1591DC]"></span>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('murid.visits') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-white/80 hover:bg-white border border-white shadow-xs transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#2C5EAD]/10 text-[#2C5EAD] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#2C5EAD]">Riwayat Kunjungan</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-[#2C5EAD] transition-all">→</span>
                    </a>

                    <a href="{{ route('murid.screenings') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-white/80 hover:bg-white border border-white shadow-xs transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#1591DC]/10 text-[#1591DC] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#1591DC]">Data Screening</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-[#1591DC] transition-all">→</span>
                    </a>

                    <a href="{{ route('murid.certificates') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-white/80 hover:bg-white border border-white shadow-xs transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#C4E2F5]/60 text-[#2C5EAD] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#2C5EAD]">Surat Keterangan</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 group-hover:text-[#2C5EAD] transition-all">→</span>
                    </a>

                    <a href="{{ route('murid.profile') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-white/80 hover:bg-white border border-white shadow-xs transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900">Profil Saya</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition-all">→</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection