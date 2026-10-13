<form method="GET" class="card row">
    <label>Periode
        <select name="period">
            @foreach(['today' => 'Hari ini', 'week' => 'Minggu ini', 'month' => 'Bulan ini', 'custom' => 'Rentang tanggal'] as $k => $v)
                <option value="{{ $k }}" @selected(($period ?? 'month') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </label>
    <label>Dari <input type="date" name="from" value="{{ request('from') }}"></label>
    <label>Sampai <input type="date" name="to" value="{{ request('to') }}"></label>
    <button class="btn">Terapkan</button>
</form>
<p class="muted">Periode: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }} (tanggal dari/sampai hanya dipakai jika memilih "Rentang tanggal")</p>