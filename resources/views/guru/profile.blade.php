@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Profil Tenaga Pendidik</h1>
            <p class="text-xs text-slate-500 mt-0.5">Informasi akun dan data kepegawaian Anda di lingkungan SMK INFORMATIKA PESAT.</p>
        </div>
        <div>
            <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Top Banner Header with #20497B and #4F69CC -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#20497B] to-[#4F69CC] text-white font-bold text-2xl flex items-center justify-center shadow-xs flex-shrink-0 ring-2 ring-[#ACE5FF]/50">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900 mb-0">{{ $teacher->name }}</h2>
                    @if($teacher->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">
                    Tenaga Pendidik / Guru • <span class="font-medium text-[#20497B]">{{ $teacher->unit?->unit_name ?? 'SMK INFORMATIKA PESAT' }}</span>
                </p>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] mb-4 mt-0">Data Identitas Kepegawaian</h3>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Nomor Induk Pegawai (NIP)</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $teacher->nip ?: '-' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Unit / Bidang Tugas</dt>
                    <dd class="text-sm font-bold text-[#20497B] mt-0.5">{{ $teacher->unit?->unit_name ?: 'SMK INFORMATIKA PESAT' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Nomor LP</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $teacher->lp_number ?: '-' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Golongan Darah</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $teacher->blood_type ?: '-' }}</dd>
                </div>
            </dl>

            <!-- Support Note -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-start gap-3 text-xs text-slate-500">
                <svg class="w-4 h-4 text-[#4F69CC] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>
                    Apabila terdapat kekeliruan data NIP atau unit kerja, hubungi bagian Tata Usaha atau administrator sekolah untuk pembaruan data sistem.
                </p>
            </div>
        </div>
    </div>

</div>

@endsection
