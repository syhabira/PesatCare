@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#20497B] to-[#4F69CC] text-white font-bold text-lg flex items-center justify-center shadow-xs ring-2 ring-[#ACE5FF]/50">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900">
                        Selamat datang, {{ $teacher->name }}
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    NIP: <span class="font-medium text-slate-700">{{ $teacher->nip ?? '-' }}</span> • 
                    Unit: <span class="font-semibold text-[#20497B] bg-[#ACE5FF]/30 px-2 py-0.5 rounded text-[11px] border border-[#ACE5FF]/60">{{ $teacher->unit?->unit_name ?? 'Tenaga Pendidik' }}</span>
                </p>
            </div>
        </div>
        <div class="text-left sm:text-right">
            <span class="text-xs text-slate-400 block">Hari ini</span>
            <span class="text-xs font-semibold text-[#20497B]">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Siswa di UKS Hari Ini (Paling penting untuk guru/piket) -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Sedang di UKS</span>
                <span class="w-2.5 h-2.5 rounded-full {{ $activeInUksCount > 0 ? 'bg-amber-500 animate-pulse' : 'bg-slate-300' }}"></span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ $activeInUksCount }}</span>
                <span class="text-xs text-slate-500 font-medium">siswa dirawat</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Perlu pemantauan guru piket</p>
        </div>

        <!-- Total Kunjungan Siswa Hari Ini -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Kunjungan Siswa Hari Ini</span>
                <div class="w-6 h-6 rounded-md bg-[#ACE5FF]/40 text-[#20497B] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-[#20497B]">{{ $todayStudentVisitsCount }}</span>
                <span class="text-xs text-slate-500 font-medium">total kunjungan</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Siswa ke UKS hari ini</p>
        </div>

        <!-- Golongan Darah Guru -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Golongan Darah</span>
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Medis</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ $teacher->blood_type ?: '-' }}</span>
                <span class="text-xs text-slate-500 font-medium">tipe darah</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Data profil kesehatan guru</p>
        </div>

        <!-- Kunjungan UKS Mandiri Guru -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Kunjungan Mandiri</span>
                <div class="w-6 h-6 rounded-md bg-[#ACE5FF]/40 text-[#4F69CC] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-[#4F69CC]">{{ $visitCount }}</span>
                <span class="text-xs text-slate-500 font-medium">kali kunjungan</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Konsultasi / obat di UKS</p>
        </div>
    </div>

    <!-- Alert Banner Pemantauan Siswa Piket with #20497B and #ACE5FF -->
    <div class="bg-[#20497B] rounded-2xl p-5 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 border border-[#4F69CC]/30 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#ACE5FF]/20 text-[#ACE5FF] border border-[#ACE5FF]/30">
                    Guru Piket & Wali Kelas
                </span>
                <h2 class="text-sm font-bold text-white mb-0">Pemantauan Siswa Sakit Real-Time</h2>
            </div>
            <p class="text-xs text-slate-200 mt-1.5 max-w-2xl leading-relaxed">
                Pantau siswa yang saat ini sedang istirahat di UKS, siswa yang telah diizinkan pulang ke rumah, atau yang sudah pulih kembali ke kelas.
            </p>
        </div>
        <div>
            <a href="{{ route('guru.monitoring') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-[#20497B] hover:bg-[#ACE5FF] hover:text-[#20497B] text-xs font-bold shadow-xs transition cursor-pointer">
                <span>Buka Pemantauan Siswa</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Section Pemeriksaan Kesehatan Terakhir -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900 m-0">Pemeriksaan Berkala Terakhir</h3>
                <p class="text-xs text-slate-500 mt-0.5">Hasil tes skrining asam urat dan kolesterol dari petugas UKS</p>
            </div>
            @if($lastCheck)
                <span class="text-xs text-[#20497B] bg-[#ACE5FF]/30 px-2.5 py-1 rounded-md font-semibold border border-[#ACE5FF]/50">
                    {{ $lastCheck->checked_at->translatedFormat('d F Y') }}
                </span>
            @endif
        </div>

        @if($lastCheck)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-xs font-semibold text-slate-500 block">Kadar Asam Urat</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-xl font-bold text-slate-900">{{ $lastCheck->uric_acid ? $lastCheck->uric_acid . ' mg/dL' : '-' }}</span>
                        @if($lastCheck->uric_acid)
                            @if($lastCheck->uric_acid <= 7.0)
                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100/70 px-2 py-0.5 rounded">Normal</span>
                            @else
                                <span class="text-[11px] font-semibold text-amber-700 bg-amber-100/70 px-2 py-0.5 rounded">Tinggi</span>
                            @endif
                        @endif
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">Nilai rujukan: 3.5 - 7.0 mg/dL</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-xs font-semibold text-slate-500 block">Kadar Kolesterol Total</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-xl font-bold text-slate-900">{{ $lastCheck->cholesterol ? $lastCheck->cholesterol . ' mg/dL' : '-' }}</span>
                        @if($lastCheck->cholesterol)
                            @if($lastCheck->cholesterol < 200)
                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100/70 px-2 py-0.5 rounded">Normal</span>
                            @else
                                <span class="text-[11px] font-semibold text-rose-700 bg-rose-100/70 px-2 py-0.5 rounded">Waspada</span>
                            @endif
                        @endif
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">Nilai rujukan: &lt; 200 mg/dL</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70">
                    <span class="text-xs font-semibold text-slate-500 block">Catatan Petugas UKS</span>
                    <p class="text-xs text-slate-700 font-medium mt-1">
                        {{ $lastCheck->notes ?: 'Kondisi kesehatan umum dalam batas wajar.' }}
                    </p>
                </div>
            </div>
        @else
            <div class="py-8 text-center">
                <p class="text-xs text-slate-500">Belum ada data pemeriksaan kesehatan guru yang tercatat.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Silakan kunjungi petugas UKS untuk melakukan pemeriksaan rutin.</p>
            </div>
        @endif
    </div>

    <!-- Riwayat Pemeriksaan Tabel Mini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 m-0">Riwayat Pemeriksaan Terkini</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar rekam medis pemeriksaan kesehatan berkala Anda</p>
            </div>
            <a href="{{ route('guru.health-checks') }}" class="text-xs font-semibold text-[#4F69CC] hover:text-[#20497B] transition">
                Lihat Semua Riwayat &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-5">Tanggal</th>
                        <th class="py-3 px-5">Asam Urat</th>
                        <th class="py-3 px-5">Kolesterol</th>
                        <th class="py-3 px-5">Gol. Darah</th>
                        <th class="py-3 px-5">Catatan Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($checks as $c)
                        <tr class="hover:bg-[#ACE5FF]/10 transition">
                            <td class="py-3 px-5 font-medium text-slate-900">{{ $c->checked_at->translatedFormat('d F Y') }}</td>
                            <td class="py-3 px-5">
                                @if($c->uric_acid)
                                    <span class="font-semibold">{{ $c->uric_acid }}</span> <span class="text-slate-400 text-[10px]">mg/dL</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                @if($c->cholesterol)
                                    <span class="font-semibold">{{ $c->cholesterol }}</span> <span class="text-slate-400 text-[10px]">mg/dL</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-5">{{ $teacher->blood_type ?? '-' }}</td>
                            <td class="py-3 px-5 text-slate-500">{{ $c->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">Belum ada riwayat data pemeriksaan kesehatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
