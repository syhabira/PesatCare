@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Pemeriksaan Kesehatan Guru</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Rekam medis pemeriksaan kadar asam urat dan kolesterol berkala oleh petugas UKS.
            </p>
        </div>
        <div>
            <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Clinical Reference Guide Box -->
    <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-2 h-2 rounded-full bg-[#4F69CC]"></span>
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#20497B] mb-0">Panduan Nilai Rujukan Medis</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                <span class="font-bold text-[#20497B]">Kadar Asam Urat (Uric Acid)</span>
                <p class="text-slate-500 text-[11px] mt-0.5">Normal Pria: 3.4 – 7.0 mg/dL • Normal Wanita: 2.4 – 6.0 mg/dL</p>
            </div>
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                <span class="font-bold text-[#20497B]">Kadar Kolesterol Total</span>
                <p class="text-slate-500 text-[11px] mt-0.5">Normal: &lt; 200 mg/dL • Batas Tinggi: 200 – 239 mg/dL • Tinggi: &ge; 240 mg/dL</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">Tabel Riwayat Pemeriksaan</h3>
            <span class="text-xs text-slate-400">Total: {{ $checks->total() }} catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-5">Tanggal Pemeriksaan</th>
                        <th class="py-3 px-5">Asam Urat</th>
                        <th class="py-3 px-5">Kolesterol Total</th>
                        <th class="py-3 px-5">Gol. Darah</th>
                        <th class="py-3 px-5">Catatan Medis Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($checks as $c)
                        <tr class="hover:bg-[#ACE5FF]/10 transition">
                            <td class="py-3.5 px-5 font-semibold text-slate-900 whitespace-nowrap">
                                {{ $c->checked_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                @if($c->uric_acid)
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $c->uric_acid }} <span class="text-[10px] text-slate-400 font-normal">mg/dL</span></span>
                                        @if($c->uric_acid <= 7.0)
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">Normal</span>
                                        @else
                                            <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">Tinggi</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                @if($c->cholesterol)
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $c->cholesterol }} <span class="text-[10px] text-slate-400 font-normal">mg/dL</span></span>
                                        @if($c->cholesterol < 200)
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">Normal</span>
                                        @elseif($c->cholesterol < 240)
                                            <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">Batas Tinggi</span>
                                        @else
                                            <span class="text-[10px] font-semibold text-rose-700 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200">Tinggi</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="font-medium text-slate-700">{{ $teacher->blood_type ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 max-w-sm">
                                {{ $c->notes ?: 'Tidak ada catatan khusus.' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Belum ada riwayat pemeriksaan kesehatan guru yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($checks->hasPages())
            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $checks->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
