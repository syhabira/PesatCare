@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Skrining Kesehatan Berkala</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Hasil pemeriksaan fisik berkala siswa (tinggi badan, berat badan, indeks massa tubuh, dan tekanan darah).
            </p>
        </div>
        <div>
            <a href="{{ route('murid.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Screenings List -->
    <div class="space-y-4">
        @forelse($screenings as $s)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-[#ACE5FF]/30 border border-[#ACE5FF]/60 text-[#20497B] flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ $s->screened_at->format('d') }}
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">{{ $s->screened_at->translatedFormat('d F Y') }}</span>
                            <span class="text-[11px] text-slate-400">Pemeriksaan Berkala UKS</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($s->weight_indicator)
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-[#ACE5FF]/30 text-[#20497B] border border-[#ACE5FF]/60">
                                {{ $s->weight_indicator }}
                            </span>
                        @endif

                        @if(config('uks.student_can_download_pdf'))
                            <a href="{{ route('murid.screenings.pdf', $s) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                                <svg class="w-3.5 h-3.5 text-[#4F69CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Unduh PDF</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Tinggi / Berat</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ $s->height_cm }} cm / {{ $s->weight_kg }} kg
                            </p>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Indeks Massa Tubuh (BMI)</span>
                            <p class="text-sm font-bold text-[#20497B] mt-1">
                                {{ $s->bmi }}
                            </p>
                            <span class="text-[10px] text-slate-500">{{ $s->weight_indicator ?: 'Normal' }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Tekanan Darah</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ $s->blood_pressure ?: '-' }}
                            </p>
                            @if($s->blood_pressure_category)
                                <span class="text-[10px] text-slate-500">{{ $s->blood_pressure_category }}</span>
                            @endif
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Lingkar Perut</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ $s->waist_cm ? $s->waist_cm . ' cm' : '-' }}
                            </p>
                            @if($s->waist_category)
                                <span class="text-[10px] text-slate-500">{{ $s->waist_category }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-12 text-center">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-sm font-bold text-slate-800 m-0">Belum Ada Data Skrining</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Data hasil skrining berkala akan ditampilkan di sini setelah Anda mengikuti pemeriksaan kesehatan fisik berkala di ruang UKS.
                </p>
            </div>
        @endforelse
    </div>

    @if($screenings->hasPages())
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs">
            {{ $screenings->links() }}
        </div>
    @endif

</div>

@endsection
