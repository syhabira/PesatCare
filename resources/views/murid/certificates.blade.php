@extends('layouts.appuser')
@section('content')

<h2>Surat Keterangan UKS</h2>
<p class="muted -mt-3 mb-4">Daftar surat keterangan sakit / izin yang diterbitkan untuk Anda.</p>

<table>
    <tr>
        <th>Tanggal Kunjungan</th>
        <th>No. Surat</th>
        <th>Keluhan</th>
        <th>Status</th>
        <th class="text-right">Aksi</th>
    </tr>
    @forelse($certs as $c)
        <tr>
            <td class="whitespace-nowrap">{{ $c->visit->visited_at->format('d/m/Y') }}</td>
            <td>{{ $c->letter_number ?? '-' }}</td>
            <td>{{ $c->visit->complaint }}</td>
            <td>
                @if($c->status_home)
                    <span class="ok" style="display:inline-block;padding:0.2rem 0.6rem;margin:0;font-size:0.8rem">Pulang</span>
                @else
                    <span class="muted">Tidak pulang</span>
                @endif
            </td>
            <td class="text-right whitespace-nowrap">
                <a href="{{ route('murid.certificates.show', $c) }}" class="btn" style="padding:0.3rem 0.75rem;font-size:0.8rem">Lihat</a>
                @if(config('uks.student_can_download_pdf'))
                    <a href="{{ route('murid.certificates.pdf', $c) }}" class="btn" style="padding:0.3rem 0.75rem;font-size:0.8rem;margin-left:0.25rem">PDF</a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="muted text-center py-8">Belum ada surat keterangan yang diterbitkan.</td>
        </tr>
    @endforelse
</table>

{{ $certs->links() }}

@endsection