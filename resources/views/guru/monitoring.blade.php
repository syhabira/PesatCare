@extends('layouts.appuser')
@section('content')

{{-- Header Page --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2>Pantau Siswa Sakit (Guru Piket)</h2>
        <p class="text-sm text-slate-500 -mt-3">Pantau siswa yang sedang dirawat di UKS, izin pulang, atau yang sudah kembali ke kelas.</p>
    </div>
    <div>
        <a href="{{ route('guru.monitoring') }}" class="btn" style="padding:0.5rem 1rem;font-size:0.8125rem">
            Refresh Data
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mt-4">
    <div class="card" style="border-left:4px solid #2C5EAD">
        Total Siswa ke UKS Hari Ini
        <b>{{ $summary['total'] }}</b>
    </div>
    <div class="card" style="border-left:4px solid #f59e0b">
        Sedang di UKS (Istirahat)
        <b style="color:#d97706">{{ $summary['di_uks'] }}</b>
    </div>
    <div class="card" style="border-left:4px solid #ef4444">
        Izin Pulang
        <b style="color:#dc2626">{{ $summary['pulang'] }}</b>
    </div>
    <div class="card" style="border-left:4px solid #10b981">
        Sudah Kembali ke Kelas
        <b style="color:#059669">{{ $summary['kembali'] }}</b>
    </div>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('guru.monitoring') }}" class="card row" style="padding:1rem;margin-bottom:1.5rem">
    <div style="flex:1;min-width:160px">
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tanggal</label>
        <input type="date" name="date" value="{{ $date }}" style="width:100%">
    </div>
    <div style="flex:1;min-width:180px">
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Filter Kelas</label>
        <select name="class_id" style="width:100%">
            <option value="">Semua Kelas</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->class_name }}</option>
            @endforeach
        </select>
    </div>
    <div style="flex:1;min-width:180px">
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status Keberadaan</label>
        <select name="status" style="width:100%">
            <option value="">Semua Status</option>
            <option value="di_uks" {{ $statusFilter === 'di_uks' ? 'selected' : '' }}>Sedang di UKS (Istirahat)</option>
            <option value="kembali_ke_kelas" {{ $statusFilter === 'kembali_ke_kelas' ? 'selected' : '' }}>Sudah Balik ke Kelas</option>
            <option value="pulang" {{ $statusFilter === 'pulang' ? 'selected' : '' }}>Izin Pulang ke Rumah</option>
        </select>
    </div>
    <div style="display:flex;gap:0.5rem">
        <button type="submit" class="btn">Filter</button>
        <a href="{{ route('guru.monitoring') }}" class="btn" style="background:#94a3b8">Reset</a>
    </div>
</form>

{{-- Table of Sick Students --}}
<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th>Waktu Masuk</th>
                <th>Siswa & Kelas</th>
                <th>Keluhan / Sakit</th>
                <th>Status & Tindakan</th>
                <th>Keterangan / Waktu Kembali</th>
                <th>Petugas UKS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $v)
                @php
                    $isHome = $v->certificate && $v->certificate->status_home;
                    $isBackToClass = ! $isHome && $v->left_at;
                    $isResting = ! $isHome && ! $v->left_at;
                @endphp
                <tr>
                    {{-- Waktu Masuk --}}
                    <td class="whitespace-nowrap font-medium text-slate-600">
                        {{ $v->visited_at->format('H:i') }} WIB
                        <span class="block text-xs text-slate-400">{{ $v->visited_at->format('d/m/Y') }}</span>
                    </td>

                    {{-- Data Siswa --}}
                    <td>
                        <span class="font-bold text-slate-800 block text-base">{{ $v->student?->name ?? $v->patient_name }}</span>
                        <span class="text-xs font-semibold text-[#2C5EAD] bg-blue-50 px-2 py-0.5 rounded-full inline-block mt-0.5">
                            {{ $v->schoolClass?->class_name ?? $v->student?->schoolClass?->class_name ?? 'Kelas -' }}
                        </span>
                        <span class="text-xs text-slate-400 ml-1">NIS: {{ $v->student?->nis ?? '-' }}</span>
                    </td>

                    {{-- Keluhan & Penyakit --}}
                    <td>
                        <div class="font-medium text-slate-700">{{ $v->complaint }}</div>
                        @if($v->diseases->count() > 0)
                            <div class="text-xs text-slate-500 mt-1">
                                Diagnosa: <span class="font-semibold text-slate-600">{{ $v->diseases->pluck('name')->join(', ') }}</span>
                            </div>
                        @endif
                        @if($v->temperature)
                            <div class="text-xs text-amber-600 font-medium mt-0.5">
                                Suhu: {{ $v->temperature }}°C
                            </div>
                        @endif
                    </td>

                    {{-- Status Badge --}}
                    <td>
                        @if($isHome)
                            <span class="bad" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem;font-weight:600">
                                🏠 Izin Pulang
                            </span>
                            @if($v->certificate->letter_number)
                                <span class="block text-[11px] text-slate-400 mt-1">No: {{ $v->certificate->letter_number }}</span>
                            @endif
                        @elseif($isBackToClass)
                            <span class="ok" style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem;font-weight:600">
                                🏫 Kembali ke Kelas
                            </span>
                        @else
                            <span style="display:inline-block;padding:0.25rem 0.75rem;margin:0;font-size:0.8rem;font-weight:600;background:#fef3c7;color:#92400e;border-radius:1rem;border:1px solid #fde68a">
                                🛏️ Sedang Istirahat UKS
                            </span>
                        @endif

                        @if($v->action)
                            <p class="text-xs text-slate-500 mt-1">Tindakan: {{ Str::limit($v->action, 45) }}</p>
                        @endif
                    </td>

                    {{-- Keterangan / Jam Pulang / Estimasi --}}
                    <td>
                        @if($isHome)
                            <div class="text-sm font-semibold text-rose-700">
                                Waktu Pulang: {{ $v->certificate->departure_time ? $v->certificate->departure_time->format('H:i') . ' WIB' : 'Hari ini' }}
                            </div>
                            @if($v->certificate->additional_notes)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $v->certificate->additional_notes }}</div>
                            @endif
                        @elseif($isBackToClass)
                            <div class="text-sm font-semibold text-emerald-700">
                                Balik ke Kelas: {{ $v->left_at->format('H:i') }} WIB
                            </div>
                            @if($v->notes)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $v->notes }}</div>
                            @endif
                        @else
                            <div class="text-sm font-medium text-amber-800">
                                Berada di UKS sejak {{ $v->visited_at->format('H:i') }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $v->notes ?: 'Dalam pemantauan petugas UKS' }}
                            </div>
                        @endif
                    </td>

                    {{-- Petugas --}}
                    <td class="text-xs text-slate-500 whitespace-nowrap">
                        {{ $v->recorder?->name ?? 'Petugas UKS' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted text-center py-10">
                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tidak ada siswa sakit yang tercatat pada tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $visits->links() }}

@endsection
