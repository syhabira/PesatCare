@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Profil Siswa & Kesehatan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Data identitas kesiswaan dan rangkuman riwayat skrining kesehatan Anda.</p>
        </div>
        <div>
            <a href="{{ route('murid.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Student Identity Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Banner Header with #20497B and #4F69CC -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#20497B] to-[#4F69CC] text-white font-bold text-2xl flex items-center justify-center shadow-xs flex-shrink-0 ring-2 ring-[#ACE5FF]/50">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900 mb-0">{{ $student->name }}</h2>
                    @if($student->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Siswa Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">
                    Siswa • Kelas <span class="font-bold text-[#20497B]">{{ $student->schoolClass?->class_name ?? '-' }}</span> • SMK INFORMATIKA PESAT
                </p>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] mb-4 mt-0">Data Pokok Siswa</h3>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Nomor Induk Siswa (NIS)</dt>
                    <dd class="text-sm font-bold text-[#20497B] mt-0.5">{{ $student->nis ?: '-' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Kelas / Rombel</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $student->schoolClass?->class_name ?: '-' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Jenis Kelamin</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-400 font-medium">Tanggal Lahir</dt>
                    <dd class="text-sm font-bold text-slate-800 mt-0.5">{{ $student->birth_date ? $student->birth_date->translatedFormat('d F Y') : '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Screening Summary Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900 m-0">Skrining Fisik Berkala Terakhir</h3>
                <p class="text-xs text-slate-500 mt-0.5">Hasil pengukuran parameter kesehatan dasar</p>
            </div>
            @if($latestScreening)
                <span class="text-xs text-[#20497B] bg-[#ACE5FF]/30 px-2.5 py-1 rounded-md font-semibold border border-[#ACE5FF]/60">
                    {{ $latestScreening->screened_at->translatedFormat('d F Y') }}
                </span>
            @endif
        </div>

        @if($latestScreening)
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium block">Tinggi Badan</span>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $latestScreening->height_cm }} cm</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium block">Berat Badan</span>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $latestScreening->weight_kg }} kg</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium block">BMI & Kategori</span>
                    <p class="text-sm font-bold text-[#4F69CC] mt-0.5">
                        {{ $latestScreening->bmi }} <span class="text-xs font-normal text-slate-600">({{ $latestScreening->weight_indicator }})</span>
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium block">Tekanan Darah</span>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">
                        {{ $latestScreening->blood_pressure ?: '-' }}
                    </p>
                </div>
            </div>

            <p class="text-[11px] text-slate-400 mt-2">
                * Parameter skrining berkala ditujukan sebagai pemantauan kondisi kebugaran dan tumbuh kembang siswa di sekolah.
            </p>
        @else
            <div class="py-8 text-center">
                <p class="text-xs text-slate-500">Belum ada catatan skrining kesehatan berkala.</p>
            </div>
        @endif
    </div>

</div>

@endsection
