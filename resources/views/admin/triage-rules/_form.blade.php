<div class="row">
    <label>Nama<input name="name" value="{{ $r?->name }}" required></label>
    <label>Parameter
        <select name="parameter">
            @foreach(['temperature' => 'Suhu', 'systolic' => 'Sistolik', 'diastolic' => 'Diastolik', 'pulse' => 'Nadi', 'disease' => 'Penyakit'] as $k => $l)
                <option value="{{ $k }}" @selected($r?->parameter === $k)>{{ $l }}</option>
            @endforeach
        </select>
    </label>
    <label>Operator
        <select name="operator">
            <option value="">-</option>
            @foreach(['gte' => '>=', 'gt' => '>', 'lte' => '<=', 'lt' => '<', 'between' => 'antara'] as $k => $l)
                <option value="{{ $k }}" @selected($r?->operator === $k)>{{ $l }}</option>
            @endforeach
        </select>
    </label>
    <label>Nilai<input type="number" step="0.1" name="value" value="{{ $r?->value !== null ? $r->value + 0 : '' }}" style="width:90px"></label>
    <label>Nilai maks (antara)<input type="number" step="0.1" name="value_max" value="{{ $r?->value_max !== null ? $r->value_max + 0 : '' }}" style="width:90px"></label>
    <label>Penyakit (jika parameter penyakit)
        <select name="disease_id"><option value="">-</option>@foreach($diseases as $d)<option value="{{ $d->id }}" @selected($r?->disease_id == $d->id)>{{ $d->name }}</option>@endforeach</select>
    </label>
    <label>Level
        <select name="level">@foreach(['hijau', 'kuning', 'merah'] as $l)<option value="{{ $l }}" @selected($r?->level === $l)>{{ ucfirst($l) }}</option>@endforeach</select>
    </label>
    <label>Pesan alasan<input name="message" value="{{ $r?->message }}"></label>
    <label>Batas penanganan (menit)<input type="number" name="deadline_minutes" value="{{ $r?->deadline_minutes }}" style="width:90px"></label>
    <label>Status<select name="is_active"><option value="1" @selected(! $r || $r->is_active)>Aktif</option><option value="0" @selected($r && ! $r->is_active)>Nonaktif</option></select></label>
</div>