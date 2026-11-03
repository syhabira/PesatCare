@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#20497B] to-[#4F69CC] text-white font-bold text-lg flex items-center justify-center shadow-xs ring-2 ring-[#ACE5FF]/50">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">
                    Halo, {{ $student->name }}
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Kelas: <span class="font-bold text-[#20497B] bg-[#ACE5FF]/30 px-2 py-0.5 rounded text-[11px] border border-[#ACE5FF]/60">{{ $student->schoolClass?->class_name ?? '-' }}</span> • 
                    NIS: <span class="font-semibold text-slate-700">{{ $student->nis }}</span>
                </p>
            </div>
        </div>
        <div class="text-left sm:text-right">
            <span class="text-xs text-slate-400 block">Hari ini</span>
            <span class="text-xs font-semibold text-[#20497B]">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Quick Stats Grid (Real Data) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Total Kunjungan UKS -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Kunjungan ke UKS</span>
                <div class="w-6 h-6 rounded-md bg-[#ACE5FF]/40 text-[#20497B] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-[#20497B]">{{ $visitCount }}</span>
                <span class="text-xs text-slate-500 font-medium">kali tercatat</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Riwayat penanganan / keluhan</p>
        </div>

        <!-- Card 2: Skrining Terakhir -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Skrining Kesehatan</span>
                <div class="w-6 h-6 rounded-md bg-[#ACE5FF]/40 text-[#4F69CC] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                @if($latestScreening)
                    <span class="text-lg font-bold text-[#4F69CC]">{{ $latestScreening->screened_at->format('d/m/Y') }}</span>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Terdata</span>
                @else
                    <span class="text-lg font-bold text-slate-400">Belum Ada</span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Pemeriksaan fisik berkala</p>
        </div>

        <!-- Card 3: Status BMI / Fisik -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Indeks Massa Tubuh (BMI)</span>
                <div class="w-6 h-6 rounded-md bg-[#ACE5FF]/40 text-[#20497B] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                @if($latestScreening)
                    <span class="text-2xl font-bold text-[#20497B]">{{ $latestScreening->bmi }}</span>
                    <span class="text-xs font-semibold text-[#20497B] bg-[#ACE5FF]/30 px-1.5 py-0.5 rounded border border-[#ACE5FF]/60">
                        {{ $latestScreening->weight_indicator ?: 'Normal' }}
                    </span>
                @else
                    <span class="text-slate-400 text-sm font-medium">Menunggu pemeriksaan</span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Berdasarkan tinggi & berat badan</p>
        </div>
    </div>

    <!-- Main Content Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left / Center: Detail Skrining Kesehatan Terakhir (Cols 1-8) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 mb-0">Hasil Skrining Kesehatan Terakhir</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Catatan pengukuran fisik siswa yang dilakukan petugas UKS</p>
                    </div>
                    @if($latestScreening)
                        <span class="text-xs text-[#20497B] bg-[#ACE5FF]/30 px-2.5 py-1 rounded-md font-semibold border border-[#ACE5FF]/50">
                            {{ $latestScreening->screened_at->translatedFormat('d F Y') }}
                        </span>
                    @endif
                </div>

                @if($latestScreening)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="text-xs text-slate-400 font-medium block">Tinggi Badan</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-xl font-bold text-slate-900">{{ $latestScreening->height_cm }}</span>
                                <span class="text-xs text-slate-500">cm</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="text-xs text-slate-400 font-medium block">Berat Badan</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-xl font-bold text-slate-900">{{ $latestScreening->weight_kg }}</span>
                                <span class="text-xs text-slate-500">kg</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="text-xs text-slate-400 font-medium block">BMI & Status</span>
                            <div class="flex items-baseline gap-1.5 mt-1">
                                <span class="text-xl font-bold text-[#4F69CC]">{{ $latestScreening->bmi }}</span>
                                <span class="text-[11px] font-semibold text-slate-600">({{ $latestScreening->weight_indicator ?? 'Normal' }})</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="text-xs text-slate-400 font-medium block">Tekanan Darah</span>
                            <div class="flex items-baseline gap-1.5 mt-1">
                                <span class="text-lg font-bold text-slate-900">{{ $latestScreening->blood_pressure ?: '-' }}</span>
                            </div>
                            @if($latestScreening->blood_pressure_category)
                                <span class="text-[10px] text-slate-500 block mt-0.5">{{ $latestScreening->blood_pressure_category }}</span>
                            @endif
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="text-xs text-slate-400 font-medium block">Lingkar Perut</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-lg font-bold text-slate-900">{{ $latestScreening->waist_cm ? $latestScreening->waist_cm . ' cm' : '-' }}</span>
                            </div>
                            @if($latestScreening->waist_category)
                                <span class="text-[10px] text-slate-500 block mt-0.5">{{ $latestScreening->waist_category }}</span>
                            @endif
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 flex flex-col justify-center">
                            <a href="{{ route('murid.screenings') }}" class="text-xs font-semibold text-[#4F69CC] hover:text-[#20497B] hover:underline block">
                                Riwayat Lengkap &rarr;
                            </a>
                            <span class="text-[10px] text-slate-400 mt-0.5">Lihat semua arsip</span>
                        </div>
                    </div>
                @else
                    <div class="py-10 text-center space-y-2">
                        <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-xs font-bold text-slate-700">Belum ada hasil skrining kesehatan yang tercatat</p>
                        <p class="text-[11px] text-slate-400 max-w-sm mx-auto">
                            Skrining kesehatan berkala diselenggarakan oleh tim UKS sekolah. Ikuti pengumuman jadwal skrining kelas Anda.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Panduan Siswa Saat Kurang Sehat -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#4F69CC]"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">Alur Layanan Siswa Kurang Sehat</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="w-5 h-5 rounded-full bg-[#ACE5FF]/50 text-[#20497B] font-bold flex items-center justify-center text-[10px]">1</span>
                        <p class="font-semibold text-slate-800">Lapor Guru</p>
                        <p class="text-slate-500 text-[11px]">Sampaikan kepada guru pengajar atau guru piket bahwa Anda kurang sehat.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="w-5 h-5 rounded-full bg-[#ACE5FF]/50 text-[#20497B] font-bold flex items-center justify-center text-[10px]">2</span>
                        <p class="font-semibold text-slate-800">Pemeriksaan UKS</p>
                        <p class="text-slate-500 text-[11px]">Petugas UKS akan mengukur suhu tubuh, mencatat keluhan, dan memberikan obat.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <span class="w-5 h-5 rounded-full bg-[#ACE5FF]/50 text-[#20497B] font-bold flex items-center justify-center text-[10px]">3</span>
                        <p class="font-semibold text-slate-800">Istirahat / Pulang</p>
                        <p class="text-slate-500 text-[11px]">Jika butuh istirahat di rumah, UKS akan menerbitkan Surat Izin Pulang resmi.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Layanan & Informasi UKS (Cols 9-12) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Dokumen Medis Siswa -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#4F69CC]"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">Dokumen Medis Anda</h3>
                </div>
                
                <div class="space-y-2">
                    <a href="{{ route('murid.certificates') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-[#ACE5FF]/20 border border-slate-200/60 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#ACE5FF]/40 text-[#20497B] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-800 block group-hover:text-[#4F69CC]">Surat Keterangan UKS</span>
                                <span class="text-[10px] text-slate-400">Bukti izin istirahat / pulang</span>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs group-hover:translate-x-0.5 group-hover:text-[#4F69CC] transition-all">&rarr;</span>
                    </a>

                    <a href="{{ route('murid.visits') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-[#ACE5FF]/20 border border-slate-200/60 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#ACE5FF]/40 text-[#4F69CC] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-800 block group-hover:text-[#4F69CC]">Riwayat Kunjungan</span>
                                <span class="text-[10px] text-slate-400">Catatan riwayat sakit</span>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs group-hover:translate-x-0.5 group-hover:text-[#4F69CC] transition-all">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Jam Operasional UKS -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#20497B] block">Informasi Ruang UKS</span>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Ruang UKS berlokasi di area utama sekolah dan buka setiap hari kerja selama kegiatan sekolah berlangsung.
                </p>
                <div class="pt-2 border-t border-slate-100 text-xs space-y-1 text-slate-500">
                    <p><span class="font-medium text-slate-700">Senin – Kamis:</span> 07.00 – 15.30 WIB</p>
                    <p><span class="font-medium text-slate-700">Jumat:</span> 07.00 – 15.00 WIB</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
