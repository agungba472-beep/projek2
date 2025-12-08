@extends('layouts.v_template')

@section('title', 'Dashboard Dosen')

@section('content')

<style>
    .card-dashboard {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
        min-height: 120px;
    }
    .card-dashboard:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,.1);
    }
    .room-card:hover {
        /* Sedikit perubahan visual pada kartu ruangan */
        border-color: #dc3545; /* Merah untuk menandakan sedang sibuk */
        background-color: #fff9f9;
    }
</style>

<div class="container-fluid py-4">
    <h3>Halo, {{ session('username') }} 👋</h3>
    <p>Selamat datang di dashboard Mahasiswa JTIK. Berikut ringkasan status peminjaman dan komplain Anda.</p>
    
    <hr class="mb-5">
    
    {{-- A. RINGKASAN UTAMA (4 Card) --}}
    <h4 class="mb-3 text-primary"><i class="fas fa-chart-line mr-2"></i> Ringkasan Status Anda</h4>
    <div class="row mb-5">
        
        {{-- 1. Total Pengajuan --}}
        <div class="col-xl-3 col-md-6 mb-4">
            {{-- Link ke daftar riwayat peminjaman ruangan --}}
            <a href="{{ route('pinjam.ruangan.index') }}" style="text-decoration: none;"> 
                <div class="card bg-info text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Riwayat Pengajuan</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalPengajuan'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clipboard-list fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 2. Peminjaman Aktif (Ruangan & Fasilitas) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('pinjam.ruangan.index') }}" style="text-decoration: none;"> 
                <div class="card bg-success text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Aktif Saat Ini</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['aktif'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-hand-holding fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 3. Peminjaman Fasilitas Aktif --}}
        <div class="col-xl-3 col-md-6 mb-4">
            {{-- Link ke daftar riwayat peminjaman fasilitas --}}
            <a href="{{ route('pinjam.fasilitas.index') }}" style="text-decoration: none;"> 
                <div class="card bg-warning text-dark shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Fasilitas Sedang Dipinjam</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['fasilitasAktif'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-laptop-code fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 4. Total Komplain --}}
        <div class="col-xl-3 col-md-6 mb-4">
            {{-- Link ke halaman komplain --}}
            <a href="{{ route('komplain.index') }}" style="text-decoration: none;"> 
                <div class="card bg-danger text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Komplain ({{ $stats['komplainProses'] }} Proses)</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalKomplain'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-headset fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <hr class="mb-5">

    {{-- B. RUANGAN KELAS YANG SEDANG SIBUK (JADWAL AKTIF) --}}
    <h4 class="mb-4 text-danger"><i class="fas fa-calendar-times mr-2"></i> Ruangan Sedang Dipakai (Jadwal Aktif)</h4>
    
    <div class="row">
        @forelse($ruanganSibuk as $peminjaman)
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                {{-- Kartu detail peminjaman --}}
                <div class="card shadow border-danger room-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-danger mb-0">{{ $peminjaman->nama_ruangan }}</h5>
                            <span class="badge bg-danger">Status: {{ ucfirst($peminjaman->status) }}</span>
                        </div>
                        <p class="card-text text-muted mt-2 mb-1"><small>Peminjam: <strong>{{ $peminjaman->nama_peminjam }}</strong></small></p>
                        <p class="card-text text-muted mb-0"><small>Mata Kuliah: {{ $peminjaman->mata_kuliah ?? '-' }}</small></p>
                        
                        <hr class="my-2">
                        
                        {{-- Logika untuk menampilkan tanggal yang relevan --}}
                        @php
                            $pinjam = \Carbon\Carbon::parse($peminjaman->jam_pinjam);
                            $kembali = \Carbon\Carbon::parse($peminjaman->jam_kembali);
                            $today = \Carbon\Carbon::today();

                            // Tentukan label tanggal
                            $dateLabel = '';
                            if ($pinjam->isSameDay($today) || $kembali->isSameDay($today)) {
                                $dateLabel = 'HARI INI';
                            } elseif ($pinjam->isSameDay($today->copy()->subDay()) || $kembali->isSameDay($today->copy()->subDay())) {
                                $dateLabel = 'KEMARIN';
                            } elseif ($pinjam->isSameDay($today->copy()->addDay()) || $kembali->isSameDay($today->copy()->addDay())) {
                                $dateLabel = 'BESOK';
                            } else {
                                $dateLabel = $pinjam->format('d M y');
                            }
                        @endphp

                        <p class="card-text">
                            <span class="badge bg-secondary">{{ $dateLabel }}</span>
                            <span class="text-dark font-weight-bold">{{ $pinjam->format('H:i') }}</span>
                            s/d
                            <span class="text-dark font-weight-bold">{{ $kembali->format('H:i') }}</span>
                            
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle mr-2"></i> Tidak ada ruangan kelas yang terjadwal dipakai untuk Kemarin, Hari Ini, dan Besok.
                </div>
            </div>
        @endforelse
    </div>
    
</div>
@endsection