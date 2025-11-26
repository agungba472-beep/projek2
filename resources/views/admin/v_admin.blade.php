@extends('layouts.v_template')

@section('title', 'Dashboard Admin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
    .card-dashboard {
        border-radius: 0.75rem;
        transition: transform 0.2s;
    }
    .card-dashboard:hover {
        transform: translateY(-3px);
    }
    .status-online { color: green; }
    .status-offline { color: red; }
</style>

<div class="container-fluid py-4">
    <h3>Halo, {{ session('username') }} 👋</h3>
    <p>Ringkasan Sistem Informasi Sarana Prasarana JTIK.</p>
    
    <hr>
    
    {{-- A. RINGKASAN (CARD STATS) --}}
<div class="row mb-4">
    
    {{-- Total Aset -> Mengarah ke Halaman Kelola Aset (aset.index) --}}
    <div class="col-xl-3 col-md-6 mb-4">
        {{-- Bungkus card dengan tag <a> yang mengarah ke route aset.index --}}
        <a href="{{ route('aset.index') }}" style="text-decoration: none;"> 
            <div class="card bg-info text-white shadow card-dashboard">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Aset</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['totalAset'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    {{-- Total Pengguna -> Mengarah ke Halaman Kelola Pengguna (pengguna.index) --}}
    <div class="col-xl-3 col-md-6 mb-4">
        {{-- Mengasumsikan ada route 'pengguna.index' --}}
        <a href="{{ route('pengguna.index') }}" style="text-decoration: none;"> 
            <div class="card bg-success text-white shadow card-dashboard">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['totalPengguna'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    {{-- Pengajuan Menunggu -> Mengarah ke Halaman Konfirmasi Pengajuan (admin.pengajuan.index) --}}
    <div class="col-xl-3 col-md-6 mb-4">
        {{-- Mengarah ke route konfirmasi pengajuan yang baru kita buat --}}
        <a href="{{ route('admin.pengajuan.index') }}" style="text-decoration: none;"> 
            <div class="card bg-warning text-dark shadow card-dashboard">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pengajuan Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['pengajuanMenunggu'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    {{-- Maintenance Terjadwal -> Mengarah ke Halaman Maintenance (maintenance.index) --}}
    <div class="col-xl-3 col-md-6 mb-4">
        {{-- Mengasumsikan ada route 'maintenance.index' --}}
        <a href="{{ route('maintenance.index') }}" style="text-decoration: none;">
            <div class="card bg-danger text-white shadow card-dashboard">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Maintenance Terjadwal</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['maintenanceTerjadwal'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<hr>
    {{-- B & E. VISUALISASI GRAFIK (ASET & MAINTENANCE) --}}
    <div class="row mb-4">
        
        {{-- B. GRAFIK ASET BERDASARKAN KONDISI --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Aset berdasarkan Kondisi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="asetKondisiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- E. GRAFIK STATUS MAINTENANCE --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pelaksanaan Maintenance</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar pt-4 pb-2">
                        <canvas id="maintenanceStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    {{-- C & D. RIWAYAT (USER ACTIVITY & PENGAJUAN TREN) --}}
    <div class="row">
        
        {{-- C. RIWAYAT ONLINE USER --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengguna Aktif dan Riwayat Terakhir ({{ $usersOnline->count() }} Online)</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($riwayatUsers as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-circle mr-2"></i> 
                                    <strong>{{ $user->username }}</strong> ({{ $user->role }})
                                </div>
                                <span class="badge {{ $user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} status-{{ $user->status }}">
                                    @if ($user->last_seen >= Carbon\Carbon::now()->subMinutes(2))
                                        Online
                                    @else
                                        Terakhir dilihat: {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Belum pernah login' }}
                                    @endif
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center">Tidak ada riwayat pengguna.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- D. GRAFIK TREN PENGAJUAN --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tren Pengajuan Peminjaman (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area pt-4 pb-2">
                        <canvas id="pengajuanTrenChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT JAVASCRIPT UNTUK GRAFIK --}}
<script>
    // Data dari Controller
    const asetKondisiData = @json($asetKondisi->pluck('total', 'kondisi'));
    const maintenanceStatusData = @json($maintenanceStatus->pluck('total', 'status'));
    const pengajuanTrenData = @json($pengajuanTren);
    
    // Warna untuk Grafik
    const colors = {
        'Baik': 'rgb(40, 167, 69)', // Success/Green
        'Rusak': 'rgb(220, 53, 69)', // Danger/Red
        'Perlu Perbaikan': 'rgb(255, 193, 7)', // Warning/Yellow
        'Terjadwal': 'rgb(255, 193, 7)',
        'Proses': 'rgb(0, 123, 255)',
        'Selesai': 'rgb(40, 167, 69)',
        'Gagal': 'rgb(108, 117, 125)',
        'default': 'rgb(0, 123, 255)' 
    };

    // Helper untuk mengubah angka bulan menjadi nama
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    const formatBulan = (monthNum, yearNum) => `${monthNames[monthNum - 1]}-${yearNum}`;


    // --- 1. GRAFIK KONDISI ASET ---
    new Chart(document.getElementById('asetKondisiChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(asetKondisiData),
            datasets: [{
                data: Object.values(asetKondisiData),
                backgroundColor: Object.keys(asetKondisiData).map(key => colors[key] || colors['default']),
                hoverBackgroundColor: Object.keys(asetKondisiData).map(key => colors[key] || colors['default']),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
    });

    // --- 2. GRAFIK STATUS MAINTENANCE ---
    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(maintenanceStatusData),
            datasets: [{
                label: "Jumlah Maintenance",
                data: Object.values(maintenanceStatusData),
                backgroundColor: Object.keys(maintenanceStatusData).map(key => colors[key] || colors['default']),
                hoverBackgroundColor: Object.keys(maintenanceStatusData).map(key => colors[key] || colors['default']),
                borderColor: '#4e73df',
            }],
        },
        options: {
            legend: { display: false },
        }
    });

    // --- 3. GRAFIK TREN PENGAJUAN ---
    // Ubah format data tren
    const trenLabels = pengajuanTrenData.map(item => formatBulan(item.bulan, item.tahun));
    const trenTotals = pengajuanTrenData.map(item => item.total);

    new Chart(document.getElementById('pengajuanTrenChart'), {
        type: 'line',
        data: {
            labels: trenLabels.reverse(), // Reverse agar bulan terbaru ada di kanan
            datasets: [{
                label: "Total Pengajuan",
                data: trenTotals.reverse(), 
                backgroundColor: 'rgba(0, 123, 255, 0.05)',
                borderColor: 'rgba(0, 123, 255, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                pointHoverRadius: 3,
                pointHitRadius: 10,
                pointBorderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
        }
    });

</script>
@endsection