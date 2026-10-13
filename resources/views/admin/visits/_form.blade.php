@php
    $v = $visit ?? null;
    $type = old('patient_type', $v ? ($v->student_id ? 'murid' : 'guru') : 'murid');
    $dt = fn ($d) => $d ? $d->format('Y-m-d\TH:i') : '';
    $checked = old('disease_ids', $v ? $v->diseases->pluck('id')->all() : []);
    
    $initialMeds = old('medicines')
        ? array_values(old('medicines'))
        : ($v ? $v->medicineUsages->groupBy(fn ($u) => $u->batch->medicine_id)
            ->map(fn ($g, $id) => ['medicine_id' => $id, 'quantity' => $g->sum('quantity'), 'notes' => $g->first()->notes])
            ->values()->all() : []);

    // Format opsi obat di blok PHP agar aman dari ParseError Blade
    $formattedMeds = $medicines->map(function ($m) {
        return [
            'id' => $m->id,
            'name' => $m->name,
            'unit' => $m->unit,
            'stock' => (int) $m->stock,
        ];
    })->values()->all();
@endphp

<form method="POST" action="{{ $action }}" id="visit-form">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="card">
        <b>Pasien</b>
        <label>Jenis pengunjung
            <select name="patient_type" id="patient_type">
                <option value="murid" @selected($type === 'murid')>Murid</option>
                <option value="guru" @selected($type === 'guru')>Guru</option>
            </select>
        </label>
        <div id="box_murid">@include('partials.patient-search', ['name' => 'student_id', 'type' => 'murid', 'value' => $v?->student_id, 'label' => $v?->student ? 'Dipilih: ' . $v->student->name . ' (' . $v->student->nis . ')' : ''])</div>
        <div id="box_guru">@include('partials.patient-search', ['name' => 'teacher_id', 'type' => 'guru', 'value' => $v?->teacher_id, 'label' => $v?->teacher ? 'Dipilih: ' . $v->teacher->name . ' (' . $v->teacher->nip . ')' : ''])</div>
    </div>

    <div class="card">
        <b>Pemeriksaan</b>
        <div class="row">
            <label>Waktu kunjungan<input type="datetime-local" name="visited_at" value="{{ old('visited_at', $dt($v?->visited_at)) }}"></label>
            <label>Suhu (°C)<input type="number" step="0.1" name="temperature" value="{{ old('temperature', $v?->temperature) }}" style="width:90px"></label>
            <label>Sistolik<input type="number" name="systolic" value="{{ old('systolic', $v?->systolic) }}" style="width:90px"></label>
            <label>Diastolik<input type="number" name="diastolic" value="{{ old('diastolic', $v?->diastolic) }}" style="width:90px"></label>
            <label>Nadi<input type="number" name="pulse" value="{{ old('pulse', $v?->pulse) }}" style="width:90px"></label>
        </div>
        <label>Keluhan<br><textarea name="complaint" rows="2" style="width:100%" required>{{ old('complaint', $v?->complaint) }}</textarea></label>
        <label>Anamnesa<br><textarea name="anamnesis" rows="2" style="width:100%">{{ old('anamnesis', $v?->anamnesis) }}</textarea></label>
        <label>Tindakan<br><textarea name="action" rows="2" style="width:100%">{{ old('action', $v?->action) }}</textarea></label>
        <label>Catatan tambahan<br><textarea name="notes" rows="2" style="width:100%">{{ old('notes', $v?->notes) }}</textarea></label>
        <div class="row">
            <label>Waktu pulang<input type="datetime-local" name="left_at" value="{{ old('left_at', $dt($v?->left_at)) }}"></label>
            <label>Status tindak lanjut<input name="follow_up_status" value="{{ old('follow_up_status', $v?->follow_up_status) }}"></label>
        </div>
    </div>

    <div class="card">
        <b>Penyakit / keluhan (pilih minimal satu)</b>
        <div class="row">
            @foreach($diseases as $d)
                <label><input type="checkbox" name="disease_ids[]" value="{{ $d->id }}" @checked(in_array($d->id, $checked))> {{ $d->name }}</label>
            @endforeach
        </div>
    </div>

    <div class="card">
        <b>Obat yang diberikan</b>
        <div id="med-rows"></div>
        <button type="button" class="btn" id="add-med">+ Tambah obat</button>
    </div>

    <div class="card" id="triage-panel">
        <b>Hasil triase (otomatis)</b>
        <div id="triage-result" class="muted">Isi suhu, tekanan darah, atau penyakit untuk melihat hasil.</div>
    </div>

    <button class="btn">Simpan</button>
    <a href="{{ route('admin.visits.index') }}">Batal</a>
</form>

<script>
(function () {
    // Tampilkan kotak pasien sesuai jenis
    const sel = document.getElementById('patient_type');
    function toggle() {
        document.getElementById('box_murid').style.display = sel.value === 'murid' ? '' : 'none';
        document.getElementById('box_guru').style.display = sel.value === 'guru' ? '' : 'none';
    }
    sel.addEventListener('change', toggle); toggle();

    // Baris obat
    const meds = {{ Illuminate\Support\Js::from($formattedMeds) }};
    const wrap = document.getElementById('med-rows');
    let idx = 0;
    function addMed(data) {
        data = data || {};
        const i = idx++;
        const row = document.createElement('div');
        row.className = 'row';
        row.style.marginBottom = '6px';

        const s = document.createElement('select');
        s.name = 'medicines[' + i + '][medicine_id]';
        meds.forEach(function (m) {
            const o = document.createElement('option');
            o.value = m.id;
            o.textContent = m.name + ' (stok ' + m.stock + ' ' + m.unit + ')';
            if (String(m.id) === String(data.medicine_id)) o.selected = true;
            s.appendChild(o);
        });
        const q = document.createElement('input');
        q.type = 'number'; q.min = 1; q.placeholder = 'Jumlah'; q.style.width = '90px';
        q.name = 'medicines[' + i + '][quantity]'; q.value = data.quantity || '';
        const n = document.createElement('input');
        n.placeholder = 'Catatan'; n.name = 'medicines[' + i + '][notes]'; n.value = data.notes || '';
        const x = document.createElement('button');
        x.type = 'button'; x.textContent = 'Hapus'; x.onclick = function () { row.remove(); };

        row.append(s, q, n, x);
        wrap.appendChild(row);
    }
    document.getElementById('add-med').onclick = function () { addMed(); };
    
    const initialMeds = {{ Illuminate\Support\Js::from($initialMeds) }};
    initialMeds.forEach(addMed);

    // Pratinjau triase
    const form = document.getElementById('visit-form');
    const out = document.getElementById('triage-result');
    const names = { hijau: 'HIJAU', kuning: 'KUNING', merah: 'MERAH' };
    const colors = { hijau: '#bbf7d0', kuning: '#fef08a', merah: '#fecaca' };
    let t;
    function preview() {
        const p = new URLSearchParams();
        ['temperature', 'systolic', 'diastolic', 'pulse'].forEach(function (k) {
            const v = form.elements[k].value;
            if (v !== '') p.append(k, v);
        });
        form.querySelectorAll('input[name="disease_ids[]"]:checked').forEach(function (c) { p.append('disease_ids[]', c.value); });
        
        const previewUrl = {{ Illuminate\Support\Js::from(route('admin.visits.triage-preview')) }};
        
        fetch(previewUrl + '?' + p.toString(), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
            .then(function (d) {
                out.replaceChildren();
                const b = document.createElement('b');
                b.textContent = 'Hasil Triase: ' + (d.level ? names[d.level] : 'BELUM DINILAI');
                b.style.cssText = 'padding:2px 8px;border-radius:4px;background:' + (colors[d.level] || '#e5e7eb');
                const r = document.createElement('div');
                r.textContent = d.reason + (d.deadline_minutes ? ' Batas penanganan: ' + d.deadline_minutes + ' menit.' : '');
                out.append(b, r);
            })
            .catch(function () { out.textContent = 'Periksa kembali nilai suhu / tekanan darah / nadi.'; });
    }
    form.addEventListener('input', function () { clearTimeout(t); t = setTimeout(preview, 400); });
    form.addEventListener('change', function () { clearTimeout(t); t = setTimeout(preview, 400); });
    preview();
})();
</script>