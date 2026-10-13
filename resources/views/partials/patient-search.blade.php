@php $uid = 'ps_' . $name; @endphp
<div id="{{ $uid }}">
    <input type="text" placeholder="Ketik nama / {{ $type === 'murid' ? 'NIS' : 'NIP' }} (min 2 huruf)" autocomplete="off" style="width:100%">
    <div class="res" style="display:none;border:1px solid #ccd;background:#fff"></div>
    <input type="hidden" name="{{ $name }}" value="{{ old($name, $value ?? '') }}">
    <div class="sel muted">{{ $label ?? '' }}</div>
</div>
<script>
(function () {
    const root = document.getElementById(@json($uid));
    const input = root.querySelector('input[type=text]');
    const res = root.querySelector('.res');
    const hidden = root.querySelector('input[type=hidden]');
    const sel = root.querySelector('.sel');
    let t;
    input.addEventListener('input', function () {
        clearTimeout(t);
        if (input.value.trim().length < 2) { res.style.display = 'none'; return; }
        t = setTimeout(async function () {
            const url = @json(route('admin.visits.patients')) + '?type=' + @json($type) + '&q=' + encodeURIComponent(input.value.trim());
            const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!r.ok) return;
            const rows = await r.json();
            res.replaceChildren();
            if (!rows.length) { res.textContent = 'Tidak ditemukan'; }
            rows.forEach(function (p) {
                const b = document.createElement('div');
                b.style.cssText = 'padding:4px 8px;cursor:pointer';
                b.textContent = p.name + ' (' + p.code + ')' + (p.group ? ' - ' + p.group : '');
                b.onclick = function () {
                    hidden.value = p.id;
                    sel.textContent = 'Dipilih: ' + b.textContent;
                    res.style.display = 'none';
                    input.value = '';
                };
                res.appendChild(b);
            });
            res.style.display = 'block';
        }, 300);
    });
})();
</script>