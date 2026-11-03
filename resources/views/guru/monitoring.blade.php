@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pemantauan Siswa Sakit (Guru Piket)</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitoring siswa yang sedang dirawat di UKS, mendapat izin pulang, atau telah kembali ke kelas.
            </p>
        </div>
        <div>
            <a href="{{ route('guru.monitoring') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition cursor-pointer">
                <svg class="w-3.5 h-3.5 text-[#4F69CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Segarkan Data</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs hover:border-[#4F69CC]/40 transition-colors">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-[#20497B] block">Total Kunjungan</span>
            <span class="text-2xl font-bold text-[#20497B] block mt-1">{{ $summary['total'] }}</span>
            <span class="text-[11px] text-slate-400">Siswa masuk UKS</span>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs hover:border-amber-300 transition-colors">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-700 block">Sedang di UKS</span>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-bold text-amber-600">{{ $summary['di_uks'] }}</span>
                <span class="text-[11px] text-amber-700 font-medium">siswa</span>
            </div>
            <span class="text-[11px] text-slate-400">Dalam perawatan/istirahat</span>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs hover:border-rose-300 transition-colors">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-rose-700 block">Izin Pulang</span>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-bold text-rose-600">{{ $summary['pulang'] }}</span>
                <span class="text-[11px] text-rose-700 font-medium">siswa</span>
            </div>
            <span class="text-[11px] text-slate-400">Diterbitkan surat izin</span>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs hover:border-emerald-300 transition-colors">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-700 block">Kembali ke Kelas</span>
            <div class="flex items-baseline gap-1.5 mt-1">
                <span class="text-2xl font-bold text-emerald-600">{{ $summary['kembali'] }}</span>
                <span class="text-[11px] text-emerald-700 font-medium">siswa</span>
            </div>
            <span class="text-[11px] text-slate-400">Sudah selesai beristirahat</span>
        </div>
    </div>

    <!-- Filter Form Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('guru.monitoring') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label for="date" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Tanggal</label>
                <input 
                    type="date" 
                    id="date"
                    name="date" 
                    value="{{ $date }}" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:border-[#4F69CC] focus:ring-4 focus:ring-[#ACE5FF]/50 outline-none transition"
                >
            </div>

            <div>
                <label for="class_id" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Kelas</label>
                <select 
                    id="class_id"
                    name="class_id" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:border-[#4F69CC] focus:ring-4 focus:ring-[#ACE5FF]/50 outline-none transition"
                >
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Status Keberadaan</label>
                <select 
                    id="status"
                    name="status" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:border-[#4F69CC] focus:ring-4 focus:ring-[#ACE5FF]/50 outline-none transition"
                >
                    <option value="">Semua Status</option>
                    <option value="di_uks" {{ $statusFilter === 'di_uks' ? 'selected' : '' }}>Sedang di UKS (Istirahat)</option>
                    <option value="kembali_ke_kelas" {{ $statusFilter === 'kembali_ke_kelas' ? 'selected' : '' }}>Kembali ke Kelas</option>
                    <option value="pulang" {{ $statusFilter === 'pulang' ? 'selected' : '' }}>Izin Pulang ke Rumah</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-[#20497B] hover:bg-[#183960] text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                    Terapkan Filter
                </button>
                <a href="{{ route('guru.monitoring') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-[#ACE5FF]/30 text-slate-700 hover:text-[#20497B] text-xs font-semibold transition cursor-pointer">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card of Sick Students -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">
                Daftar Kunjungan Siswa: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
            </h3>
            <span class="text-xs text-slate-400">Total: {{ $visits->total() }} catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Siswa & Kelas</th>
                        <th class="py-3 px-4">Keluhan / Diagnosa</th>
                        <th class="py-3 px-4">Status Siswa</th>
                        <th class="py-3 px-4">Tindakan & Catatan</th>
                        <th class="py-3 px-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($visits as $v)
                        @php
                            $isHome = $v->certificate && $v->certificate->status_home;
                            $isBackToClass = ! $isHome && $v->left_at;
                            $isResting = ! $isHome && ! $v->left_at;
                        @endphp
                        <tr class="hover:bg-[#ACE5FF]/10 transition">
                            <!-- Waktu Masuk -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $v->visited_at->format('H:i') }} WIB</span>
                                <span class="text-[11px] text-slate-400">{{ $v->visited_at->format('d/m/Y') }}</span>
                            </td>

                            <!-- Data Siswa -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block text-xs">{{ $v->student?->name ?? $v->patient_name }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-[#ACE5FF]/30 text-[#20497B] border border-[#ACE5FF]/60">
                                        {{ $v->schoolClass?->class_name ?? $v->student?->schoolClass?->class_name ?? 'Kelas -' }}
                                    </span>
                                    @if($v->student?->nis)
                                        <span class="text-[11px] text-slate-400">NIS: {{ $v->student->nis }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Keluhan & Diagnosa -->
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="font-medium text-slate-800">{{ $v->complaint }}</p>
                                @if($v->diseases->count() > 0)
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        Diagnosa: <span class="font-semibold text-slate-700">{{ $v->diseases->pluck('name')->join(', ') }}</span>
                                    </div>
                                @endif
                                @if($v->temperature)
                                    <span class="inline-block mt-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">
                                        Suhu: {{ $v->temperature }}°C
                                    </span>
                                @endif
                            </td>

                            <!-- Status Keberadaan -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($isHome)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Izin Pulang
                                    </span>
                                    @if($v->certificate->letter_number)
                                        <span class="block text-[10px] text-slate-400 mt-0.5">No: {{ $v->certificate->letter_number }}</span>
                                    @endif
                                @elseif($isBackToClass)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kembali ke Kelas
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">Pukul {{ $v->left_at->format('H:i') }} WIB</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Sedang di UKS
                                    </span>
                                    <span class="block text-[10px] text-amber-600 mt-0.5">Sejak {{ $v->visited_at->format('H:i') }} WIB</span>
                                @endif
                            </td>

                            <!-- Tindakan & Catatan Medis -->
                            <td class="py-3.5 px-4 max-w-xs">
                                @if($v->action)
                                    <p class="text-xs text-slate-700 font-medium">{{ $v->action }}</p>
                                @endif
                                @if($isHome && $v->certificate?->additional_notes)
                                    <p class="text-[11px] text-slate-500 mt-0.5 italic">{{ $v->certificate->additional_notes }}</p>
                                @elseif($v->notes)
                                    <p class="text-[11px] text-slate-500 mt-0.5 italic">{{ $v->notes }}</p>
                                @endif
                                @if(! $v->action && ! $v->notes && ! ($isHome && $v->certificate?->additional_notes))
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Petugas -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 text-xs">
                                {{ $v->recorder?->name ?? 'Petugas UKS' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Tidak ada catatan kunjungan siswa pada tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $visits->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
