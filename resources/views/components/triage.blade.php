@props(['level' => null])
@php
    $map = ['hijau' => ['Hijau', '#bbf7d0'], 'kuning' => ['Kuning', '#fef08a'], 'merah' => ['Merah', '#fecaca']];
    [$text, $bg] = $map[$level] ?? ['Belum dinilai', '#e5e7eb'];
@endphp
<span style="padding:2px 8px;border-radius:4px;background:{{ $bg }};font-weight:600">{{ $text }}</span>