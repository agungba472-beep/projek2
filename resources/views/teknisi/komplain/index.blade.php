@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Komplain Ditugaskan ke Saya</h4>
            {{-- Tambahkan elemen lain di header jika diperlukan, misalnya tombol refresh/filter --}}
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            {{-- KOLOM UNTUK SLA --}}
                            <th>Waktu Tersisa SLA</th> 
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kategori }}</td>
                            <td>{{ Illuminate\Support\Str::limit($d->deskripsi, 50) }}</td>
                            <td>
                                {{-- Menggunakan Badge untuk Status agar terlihat lebih baik --}}
                                <span class="badge 
                                    @if($d->status=='baru') bg-danger 
                                    @elseif($d->status=='diproses') bg-primary 
                                    @elseif($d->status=='selesai') bg-success 
                                    @else bg-secondary 
                                    @endif">
                                    {{ $d->status }}
                                </span>
                            </td>
                            
                            {{-- KOLOM LOGIKA SLA --}}
                            <td>
                                @php
                                    // SOLUSI: Langsung panggil Carbon secara global (\Carbon\Carbon)
                                    $deadline = $d->sla_deadline ? \Carbon\Carbon::parse($d->sla_deadline) : null;
                                @endphp

                                @if ($d->status == 'selesai')
                                    <span class="text-success">Selesai Dikerjakan</span>
                                @elseif ($deadline)
                                    @if (\Carbon\Carbon::now()->greaterThan($deadline))
                                        {{-- Jika Terlambat (Waktu Sekarang > Deadline) --}}
                                        <span class="text-danger font-weight-bold">TERLAMBAT</span>
                                        <small class="d-block text-danger">({{ $deadline->diffForHumans(null, true) }} lalu)</small>
                                    @else
                                        @php
                                            // Menghitung sisa waktu dalam Hari, Jam, Menit
                                            $remaining = \Carbon\Carbon::now()->diff($deadline);
                                            $text = [];
                                            
                                            if ($remaining->days > 0) $text[] = $remaining->days . ' Hari';
                                            if ($remaining->h > 0) $text[] = $remaining->h . ' Jam';
                                            // Tampilkan menit jika tidak ada Hari/Jam, atau jika sisa waktu kurang dari 1 jam.
                                            if ($remaining->i > 0 && ($remaining->days == 0 && $remaining->h < 1) ) $text[] = $remaining->i . ' Menit'; 
                                            
                                            $displayText = empty($text) ? 'Segera (< 1m)' : implode(', ', $text);

                                            // Tentukan warna berdasarkan sisa waktu
                                            $isUrgent = $remaining->days == 0 && $remaining->h < 6; // Kurang dari 6 jam
                                            
                                            $textColor = 'text-primary';
                                            if ($isUrgent) {
                                                $textColor = 'text-danger';
                                            } elseif ($remaining->days == 0) {
                                                $textColor = 'text-warning'; // Kurang dari 1 hari
                                            }
                                        @endphp
                                        <span class="{{ $textColor }} font-weight-bold">
                                            Sisa: {{ $displayText }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-secondary">SLA Belum Ditetapkan</span>
                                @endif
                            </td>
                            
                            <td>
                                <a href="{{ route('teknisi.komplain.show', $d->komplain_id) }}" class="btn btn-info btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Tambahkan paginasi jika diperlukan: {{ $data->links() }} --}}
        </div>
    </div>
</div>
@endsection