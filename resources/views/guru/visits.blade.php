@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Kunjungan UKS Guru</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Daftar catatan kunjungan, keluhan, dan tindakan medis pribadi Anda di ruang UKS.
            </p>
        </div>
        <div>
            <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Visits Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">Tabel Riwayat Kunjungan</h3>
            <span class="text-xs text-slate-400">Total: {{ $visits->total() }} kunjungan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-5">Waktu Kunjungan</th>
                        <th class="py-3 px-5">Keluhan</th>
                        <th class="py-3 px-5">Diagnosa / Masalah</th>
                        <th class="py-3 px-5">Tindakan & Obat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($visits as $v)
                        <tr class="hover:bg-[#ACE5FF]/10 transition">
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $v->visited_at->translatedFormat('d F Y') }}</span>
                                <span class="text-[11px] text-slate-400">Pukul {{ $v->visited_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-3.5 px-5 max-w-xs font-medium text-slate-800">
                                {{ $v->complaint }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($v->diseases->count() > 0)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-[#ACE5FF]/30 text-[#20497B] border border-[#ACE5FF]/60">
                                        {{ $v->diseases->pluck('name')->join(', ') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 max-w-sm">
                                {{ $v->action ?: 'Istirahat / Observasi petugas UKS' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Belum ada catatan kunjungan UKS yang tercatat.</span>
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
