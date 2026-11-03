@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Surat Keterangan UKS & Izin Sakit</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Daftar dokumen resmi surat keterangan istirahat dan surat izin pulang yang diterbitkan oleh petugas UKS.
            </p>
        </div>
        <div>
            <a href="{{ route('murid.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:border-[#4F69CC]/40 hover:bg-[#ACE5FF]/10 text-xs font-semibold shadow-xs transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Certificates Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#20497B] m-0">Daftar Dokumen Surat</h3>
            <span class="text-xs text-slate-400">Total: {{ $certs->total() }} surat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-5">Tanggal Terbit</th>
                        <th class="py-3 px-5">Nomor Surat</th>
                        <th class="py-3 px-5">Keluhan Sakit</th>
                        <th class="py-3 px-5">Status Izin</th>
                        <th class="py-3 px-5 text-right">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($certs as $c)
                        <tr class="hover:bg-[#ACE5FF]/10 transition">
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $c->visit->visited_at->translatedFormat('d F Y') }}</span>
                                <span class="text-[11px] text-slate-400">Pukul {{ $c->visit->visited_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-3.5 px-5 font-mono text-[#20497B] font-bold">
                                {{ $c->letter_number ?: '-' }}
                            </td>
                            <td class="py-3.5 px-5 max-w-xs font-medium text-slate-800">
                                {{ $c->visit->complaint }}
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                @if($c->status_home)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Izin Pulang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#ACE5FF]/30 text-[#20497B] border border-[#ACE5FF]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#4F69CC]"></span>
                                        Istirahat di UKS
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('murid.certificates.show', $c) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-[#4F69CC] hover:bg-[#ACE5FF]/20 text-xs font-semibold shadow-xs transition">
                                        Lihat
                                    </a>
                                    @if(config('uks.student_can_download_pdf'))
                                        <a href="{{ route('murid.certificates.pdf', $c) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#20497B] hover:bg-[#4F69CC] text-white text-xs font-semibold shadow-xs transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span>PDF</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Belum ada surat keterangan UKS yang diterbitkan untuk Anda.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certs->hasPages())
            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $certs->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
