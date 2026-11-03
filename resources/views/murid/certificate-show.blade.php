@extends('layouts.appuser')

@section('content')
@php 
    $v = $certificate->visit; 
@endphp

<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Top Action Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('murid.certificates') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
            &larr; Kembali ke Daftar Surat
        </a>

        @if(config('uks.student_can_download_pdf'))
            <a href="{{ route('murid.certificates.pdf', $certificate) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#20497B] hover:bg-[#4F69CC] text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Unduh Dokumen PDF Resmi</span>
            </a>
        @endif
    </div>

    <!-- Official Certificate Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <!-- Letterhead Header -->
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-[#20497B] text-white font-bold flex items-center justify-center text-sm shadow-xs flex-shrink-0 ring-2 ring-[#ACE5FF]/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-0">Surat Keterangan Layanan UKS</h2>
                    <p class="text-xs text-slate-500">Unit Kesehatan Sekolah • SMK INFORMATIKA PESAT</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-[11px] text-slate-400 block font-medium">Nomor Dokumen</span>
                <span class="text-xs font-mono font-bold text-[#20497B] bg-[#ACE5FF]/30 px-2 py-0.5 rounded border border-[#ACE5FF]/60">{{ $certificate->letter_number ?: 'Dalam Proses' }}</span>
            </div>
        </div>

        <!-- Certificate Status Banner -->
        <div class="px-6 sm:px-8 py-3.5 border-b border-slate-100 {{ $certificate->status_home ? 'bg-rose-50/70 text-rose-800' : 'bg-[#ACE5FF]/20 text-[#20497B]' }} flex items-center justify-between text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $certificate->status_home ? 'bg-rose-500' : 'bg-[#4F69CC]' }}"></span>
                <span>Keterangan Status: {{ $certificate->status_home ? 'Diberikan Izin Pulang ke Rumah' : 'Istirahat Sementara di Ruang UKS' }}</span>
            </div>
            @if($certificate->departure_time)
                <span class="text-[11px] font-medium">Pukul {{ $certificate->departure_time->format('H:i') }} WIB</span>
            @endif
        </div>

        <!-- Letter Body Content -->
        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Student Information Section -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] mb-3 mt-0">Identitas Siswa</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <dt class="text-slate-400 font-medium">Nama Siswa</dt>
                        <dd class="text-sm font-bold text-slate-900 mt-0.5">{{ $v->student?->name }}</dd>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <dt class="text-slate-400 font-medium">Nomor Induk Siswa (NIS)</dt>
                        <dd class="text-sm font-bold text-[#20497B] mt-0.5">{{ $v->student?->nis ?: '-' }}</dd>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <dt class="text-slate-400 font-medium">Kelas / Rombel</dt>
                        <dd class="text-sm font-bold text-slate-900 mt-0.5">{{ $v->student?->schoolClass?->class_name ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Medical Findings Section -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] mb-3 mt-0">Rincian Medis & Penanganan UKS</h3>
                <div class="space-y-3 text-xs">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 font-medium block">Waktu Kunjungan</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $v->visited_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 font-medium block">Keluhan yang Dirasakan</span>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $v->complaint }}</p>
                    </div>

                    @if($v->diseases->count() > 0)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Diagnosa Medis</span>
                            <p class="text-sm font-semibold text-[#20497B] mt-0.5">{{ $v->diseases->pluck('name')->join(', ') }}</p>
                        </div>
                    @endif

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 font-medium block">Tindakan / Terapi UKS</span>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $v->action ?: 'Observasi & istirahat di ruang UKS' }}</p>
                    </div>

                    @if($certificate->additional_notes)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block">Catatan Tambahan Petugas</span>
                            <p class="text-xs text-slate-700 mt-0.5 italic">{{ $certificate->additional_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Sign Notice -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>Dokumen ini diterbitkan sah oleh Petugas Ruang UKS SMK INFORMATIKA PESAT.</span>
                <span class="font-bold text-[#20497B]">PesatCare Digital</span>
            </div>

        </div>
    </div>

</div>

@endsection
