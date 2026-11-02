@extends('layouts.appuser')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto pb-6">

    <!-- ========================================================= -->
    <!-- PAGE HEADER                                               -->
    <!-- ========================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Pemeriksaan Kesehatan</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Rekam jejak pemeriksaan asam urat dan kolesterol yang dicatat oleh petugas UKS[cite: 8].
            </p>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- DATA TABLE CARD (GLASSMORPHISM 60% OPACITY)               -->
    <!-- ========================================================= -->
    <div class="bg-white/60 backdrop-blur-xl rounded-[28px] border border-white/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200/60 bg-white/40 text-slate-500 uppercase tracking-wider text-[10px]">
                        <th class="py-4 px-6 font-bold">Tanggal</th>
                        <th class="py-4 px-6 font-bold">Asam Urat (mg/dL)</th>
                        <th class="py-4 px-6 font-bold">Kolesterol (mg/dL)</th>
                        <th class="py-4 px-6 font-bold">Gol. Darah</th>
                        <th class="py-4 px-6 font-bold">Catatan Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($checks as $c)
                        <tr class="hover:bg-white/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ $c->checked_at->format('d F Y') }}[cite: 8]</td>
                            <td class="py-4 px-6 text-slate-700 font-medium">{{ $c->uric_acid ?? '-' }}[cite: 8]</td>
                            <td class="py-4 px-6 text-slate-700 font-medium">{{ $c->cholesterol ?? '-' }}[cite: 8]</td>
                            <td class="py-4 px-6 text-slate-700 font-medium">{{ $teacher->blood_type ?? '-' }}[cite: 8]</td>
                            <td class="py-4 px-6 text-slate-500">{{ $c->notes ?: '-' }}[cite: 8]</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400 font-medium">
                                Belum ada data pemeriksaan kesehatan[cite: 8].
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if(method_exists($checks, 'links'))
            <div class="px-6 py-4 border-t border-slate-200/60 bg-white/40 flex items-center justify-between">
                {{ $checks->links() }}
            </div>
        @endif
    </div>

</div>

@endsection