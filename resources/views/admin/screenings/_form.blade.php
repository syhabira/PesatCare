@php $s = $screening ?? null; @endphp
<form method="POST" action="{{ $action }}" class="card">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    @if($s)
        <input type="hidden" name="student_id" value="{{ $s->student_id }}">
    @else
        <b>Siswa</b>
        @include('partials.patient-search', ['name' => 'student_id', 'type' => 'murid'])
        <br>
    @endif

    <div class="row">
        <label>Tanggal pemeriksaan<input type="date" name="screened_at" value="{{ old('screened_at', $s?->screened_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></label>
        <label>Tinggi badan (cm)<input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $s?->height_cm) }}" required style="width:100px"></label>
        <label>Berat badan (kg)<input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $s?->weight_kg) }}" required style="width:100px"></label>
        <label>Lingkar perut (cm)<input type="number" step="0.1" name="waist_cm" value="{{ old('waist_cm', $s?->waist_cm) }}" style="width:100px"></label>
        <label>Sistolik<input type="number" name="systolic" value="{{ old('systolic', $s?->systolic) }}" style="width:90px"></label>
        <label>Diastolik<input type="number" name="diastolic" value="{{ old('diastolic', $s?->diastolic) }}" style="width:90px"></label>
    </div>
    <p class="muted">BMI dan semua kategori dihitung otomatis oleh sistem setelah disimpan.</p>
    <button class="btn">Simpan</button>
    <a href="{{ route('admin.screenings.index') }}">Batal</a>
</form>