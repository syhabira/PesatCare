@extends('layouts.app')
@section('content')
<div class="row" style="justify-content:space-between">
    <h2>Detail Kunjungan</h2>
    <div class="row">
        <a class="btn" href="{{ route('admin.visits.edit', $visit) }}">Edit</a>
        <form method="POST" action="{{ route('admin.visits.destroy', $visit) }}" onsubmit="return confirm('Hapus kunjungan ini? Stok obat akan dikembalikan.')">
            @csrf @method('DELETE') <button class="btn btn-red">Hapus</button>
        </form>
    </div>
</div>

<div class="card">
    <table>
        <tr><th width="30%">Pasien</th><td>{{ $visit->patient_name }} ({{ $visit->student ? 'Murid' : 'Guru' }})</td></tr>
        <tr><th>NIS / NIP</th><td>{{ $visit->student?->nis ?? $visit->teacher?->nip }}</td></tr>
        <tr><th>Kelas / Unit</th><td>{{ $visit->schoolClass?->class_name ?? $visit->unit?->unit_name ?? '-' }}</td></tr>
        <tr><th>Waktu kunjungan</th><td>{{ $visit->visited_at->format('d/m/Y H:i') }}</td></tr>
        <tr><th>Dicatat oleh</th><td>{{ $visit->recorder->name }}@if($visit->updater) <span class="muted">(terakhir diubah: {{ $visit->updater->name }})</span>@endif</td></tr>
        <tr><th>Suhu</th><td>{{ $visit->temperature ? $visit->temperature . ' °C' : '-' }}</td></tr>
        <tr><th>Tekanan darah</th><td>{{ $visit->blood_pressure ?? '-' }}</td></tr>
        <tr><th>Nadi</th><td>{{ $visit->pulse ?? '-' }}</td></tr>
        <tr><th>Keluhan</th><td>{{ $visit->complaint }}</td></tr>
        <tr><th>Anamnesa</th><td>{{ $visit->anamnesis ?? '-' }}</td></tr>
        <tr><th>Penyakit</th><td>{{ $visit->diseases->pluck('name')->join(', ') }}</td></tr>
        <tr><th>Tindakan</th><td>{{ $visit->action ?? '-' }}</td></tr>
        <tr><th>Catatan</th><td>{{ $visit->notes ?? '-' }}</td></tr>
        <tr><th>Waktu pulang</th><td>{{ $visit->left_at?->format('d/m/Y H:i') ?? '-' }}</td></tr>
        <tr><th>Tindak lanjut</th><td>{{ $visit->follow_up_status ?? '-' }}</td></tr>
    </table>
</div>

<div class="card">
    <b>Hasil Triase:</b> <x-triage :level="$visit->triage_level" />
    <p>{{ $visit->triage_reason }}</p>
    @if($visit->triage_deadline)
        <p>Batas penanganan: {{ $visit->triage_deadline->format('d/m/Y H:i') }}
        - Status: {{ ['not_required' => 'Tidak diperlukan', 'pending' => 'Menunggu', 'handled' => 'Sudah ditangani'][$visit->triage_status] }}
        @if($visit->isTriageOverdue()) <b style="color:#b91c1c">(TERLAMBAT)</b>@endif</p>
    @endif
    @if($visit->triage_status === 'pending')
        <form method="POST" action="{{ route('admin.visits.triage-handled', $visit) }}">@csrf @method('PATCH') <button class="btn">Tandai sudah ditangani</button></form>
    @endif
</div>

<div class="card">
    <b>Obat diberikan</b>
    <table>
        <tr><th>Obat</th><th>Batch</th><th>Jumlah</th></tr>
        @forelse($visit->medicineUsages as $u)
            <tr><td>{{ $u->batch->medicine->name }}</td><td>{{ $u->batch->batch_number }}</td><td>{{ $u->quantity }} {{ $u->batch->medicine->unit }}</td></tr>
        @empty
            <tr><td colspan="3" class="muted">Tidak ada obat diberikan.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <b>Surat keterangan</b>
    @if($visit->certificate)
        <p><a href="{{ route('admin.certificates.show', $visit->certificate) }}">Lihat surat</a> -
        <a href="{{ route('admin.certificates.pdf', $visit->certificate) }}">Unduh PDF</a></p>
    @else
        <form method="POST" action="{{ route('admin.visits.certificate.store', $visit) }}" class="row">
            @csrf
            <label>Nomor surat (opsional)<input name="letter_number"></label>
            <label>Status<select name="status_home"><option value="0">Tidak pulang</option><option value="1">Pulang</option></select></label>
            <label>Waktu pulang (wajib jika pulang)<input type="datetime-local" name="departure_time"></label>
            <label>Catatan tambahan<input name="additional_notes"></label>
            <button class="btn">Buat surat</button>
        </form>
    @endif
</div>
<a href="{{ route('admin.visits.index') }}">&larr; Kembali</a>
@endsection