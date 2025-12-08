@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Riwayat Semua Data Komplain</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Pelapor</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Level Kerusakan</th>
                            <th>SLA (Deadline)</th>
                            <th>Status</th>
                            <th>Teknisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->user->username ?? 'N/A' }}</td>
                            <td>{{ $d->kategori }}</td>
                            <td>{{ Illuminate\Support\Str::limit($d->deskripsi, 50) }}</td>
                            <td>
                                @if($d->level_teknisi)
                                    <span class="badge 
                                        @if($d->level_teknisi == 'major') bg-danger 
                                        @elseif($d->level_teknisi == 'minor') bg-warning text-dark 
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($d->level_teknisi) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum Diisi</span>
                                @endif
                            </td>
                            <td>
                                @if($d->level_teknisi)
                                    @php
                                        // ** LOGIKA SLA **
                                        // Major = 3 Hari, Minor = 1 Hari
                                        $sla_days = ($d->level_teknisi == 'major') ? 3 : 1;
                                        
                                        // Gunakan sla_deadline jika ada, jika tidak, hitung dari created_at
                                        $deadline = $d->sla_deadline 
                                                    ? \Carbon\Carbon::parse($d->sla_deadline) 
                                                    : \Carbon\Carbon::parse($d->created_at)->addDays($sla_days);

                                        // Cek apakah sudah melewati deadline dan status belum selesai
                                        $is_late = ($d->status != 'selesai' && now()->greaterThan($deadline));
                                    @endphp

                                    <span class="badge {{ $is_late ? 'bg-danger' : 'bg-success' }}">
                                        {{ $sla_days }} Hari
                                    </span>
                                    <br>
                                    <small class="{{ $is_late ? 'text-danger' : 'text-success' }}">
                                        Deadline: {{ $deadline->format('d M y H:i') }}
                                    </small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($d->status=='baru') bg-danger 
                                    @elseif($d->status=='diproses') bg-primary 
                                    @elseif($d->status=='selesai') bg-success 
                                    @else bg-secondary 
                                    @endif">
                                    {{ ucfirst($d->status) }}
                                </span>
                            </td>
                            {{-- SOLUSI: Menggunakan $d->teknisi untuk memanggil relasi teknisi --}}
                            <td>{{ $d->teknisi->username ?? '-' }}</td> 
                            <td>
                                {{-- Asumsi route detail komplain admin: 'admin.komplain.show' --}}
                                <a href="{{ route('admin.komplain.show', $d->komplain_id) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection