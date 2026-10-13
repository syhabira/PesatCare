<?php

namespace App\Services;

use App\Models\TriageRule;

class TriageService
{
    private const ORDER = ['hijau' => 1, 'kuning' => 2, 'merah' => 3];

    /**
     * @param array $in temperature, systolic, diastolic, pulse, disease_ids[]
     * @return array{level:?string,reason:string,deadline_minutes:?int,snapshot:array}
     */
    public function evaluate(array $in): array
    {
        $rules = TriageRule::active()
            ->when(config('uks.triage_require_validated'), fn ($q) => $q->where('is_validated', true))
            ->get();

        if ($rules->isEmpty()) {
            return ['level' => null, 'deadline_minutes' => null, 'snapshot' => ['matched' => [], 'missing' => []],
                'reason' => 'Belum ada aturan triase aktif, kunjungan belum dinilai.'];
        }

        $diseaseIds = array_map('intval', $in['disease_ids'] ?? []);
        $matched = $rules->filter(fn ($r) => $this->matches($r, $in, $diseaseIds));

        $missing = [];
        foreach (['temperature' => 'suhu', 'systolic' => 'tekanan darah', 'pulse' => 'nadi'] as $k => $label) {
            if (! isset($in[$k]) || $in[$k] === '') {
                $missing[] = $label;
            }
        }

        if ($matched->isEmpty()) {
            $level = 'hijau';
            $reason = 'Tidak ditemukan indikator prioritas tinggi berdasarkan aturan yang berlaku.';
        } else {
            $level = $matched->sortByDesc(fn ($r) => self::ORDER[$r->level])->first()->level;
            $reason = 'Terdeteksi: ' . $matched
                ->sortByDesc(fn ($r) => self::ORDER[$r->level])
                ->map(fn ($r) => $r->message ?: $r->name)
                ->implode('; ') . '.';
        }

        if ($missing) {
            $reason .= ' Data belum lengkap: ' . implode(', ', $missing) . ' (tidak dapat dievaluasi).';
        }
        if ($rules->contains(fn ($r) => ! $r->is_validated)) {
            $reason .= ' Catatan: sebagian aturan triase belum disahkan pihak sekolah.';
        }

        $deadline = $matched->where('level', $level)->pluck('deadline_minutes')->filter(fn ($v) => $v !== null)->min();

        return [
            'level' => $level,
            'reason' => $reason,
            'deadline_minutes' => $deadline !== null ? (int) $deadline : null,
            'snapshot' => [
                'matched' => $matched->map(fn ($r) => $r->only([
                    'id', 'name', 'parameter', 'operator', 'value', 'value_max',
                    'disease_id', 'level', 'version', 'is_validated',
                ]))->values()->all(),
                'missing' => $missing,
            ],
        ];
    }

    private function matches(TriageRule $r, array $in, array $diseaseIds): bool
    {
        if ($r->parameter === 'disease') {
            return $r->disease_id !== null && in_array((int) $r->disease_id, $diseaseIds, true);
        }

        $v = $in[$r->parameter] ?? null;
        if ($v === null || $v === '') {
            return false; // data kosong tidak memicu aturan
        }
        $v = (float) $v;
        $a = (float) $r->value;

        return match ($r->operator) {
            'gte' => $v >= $a,
            'gt' => $v > $a,
            'lte' => $v <= $a,
            'lt' => $v < $a,
            'between' => $v >= $a && $v <= (float) $r->value_max,
            default => false,
        };
    }
}