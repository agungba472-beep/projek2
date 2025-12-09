@extends('layouts.v_template')

@section('title', 'Dashboard Admin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
    .card-dashboard {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
        min-height: 100px; /* Memastikan tinggi minimum agar terlihat seragam */
    }
    .card-dashboard:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,.15); /* Bayangan yang lebih kuat saat hover */
    }
    .status-online { color: green; }
    .status-offline { color: red; }
</style>

<div class="container-fluid py-4">
    <h3>Halo, {{ session('username') }} 👋</h3>
    <p>Ringkasan Sistem Informasi Sarana Prasarana JTIK.</p>
    
    <hr>
    
    {{-- A. RINGKASAN KRITIS (5 STATS CARD) --}}
    <div class="row mb-4">
        
        {{-- Total Aset --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('aset.index') }}" style="text-decoration: none;"> 
                <div class="card bg-info text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Aset</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalAset'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-boxes fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- Total Pengguna --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('pengguna.index') }}" style="text-decoration: none;"> 
                <div class="card bg-success text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Pengguna</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalPengguna'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-users fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- Peminjaman Menunggu (ACTION REQUIRED) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.pengajuan.index') }}" style="text-decoration: none;"> 
                <div class="card bg-warning text-dark shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Peminjaman Menunggu</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['pengajuanMenunggu'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clock fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- NEW CARD: Total Komplain --}}
        <div class="col-xl-3 col-md-6 mb-4">
            {{-- Mengarah ke halaman Riwayat Komplain Admin --}}
            <a href="{{ route('admin.komplain.riwayat') }}" style="text-decoration: none;"> 
                <div class="card bg-secondary text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Riwayat Komplain</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalKomplain'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-history fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    
    {{-- BARIS 2: METRIK KRITIS (Komplain Aktif & Maintenance Terjadwal) --}}
    <div class="row mb-4">
        
        {{-- NEW CARD: Komplain Aktif (ACTION REQUIRED) --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            {{-- Mengarah ke halaman komplain aktif yang perlu ditangani --}}
            <a href="{{ route('admin.komplain.riwayat', ['status' => 'aktif']) }}" style="text-decoration: none;"> 
                <div class="card bg-primary text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Komplain Aktif & Belum Selesai</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $stats['komplainAktif'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-headset fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Maintenance Terjadwal --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <a href="{{ route('maintenance.index') }}" style="text-decoration: none;">
                <div class="card bg-danger text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Maintenance Terjadwal</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $stats['maintenanceTerjadwal'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-wrench fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    
    <hr>
    
    {{-- C. VISUALISASI GRAFIK (3 Charts) --}}
    <div class="row mb-4">
        
        {{-- F. GRAFIK BARU: KOMPLAIN SLA BREAKDOWN --}}
        <div class="col-xl-4 col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Komplain Berdasarkan Level SLA</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="komplainSLAChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Major (SLA 3 Hari)</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Minor (SLA 1 Hari)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- B. GRAFIK ASET BERDASARKAN KONDISI --}}
        <div class="col-xl-4 col-lg-4 mb-4">
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
        <div class="col-xl-4 col-lg-4 mb-4">
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
    
    {{-- D & G. RIWAYAT & TREN --}}
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
                                <span class="badge 
                                    @if ($user->last_seen && $user->last_seen >= Carbon\Carbon::now()->subMinutes(5)) bg-success 
                                    @else bg-secondary 
                                    @endif">
                                    @if ($user->last_seen && $user->last_seen >= Carbon\Carbon::now()->subMinutes(5))
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
                    <h6 class="m-0 font-weight-bold text-primary">Tren  Peminjaman (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area pt-4 pb-2">
                        <canvas id="pengajuanTrenChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Aset Rusak Baru Bulan Ini ({{ Carbon\Carbon::now()->translatedFormat('F Y') }})
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $laporan_rusak_bulan_ini }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-tools fa-2x text-gray-300"></i>
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
    const komplainSLAStatusData = @json($komplainSLAStatus->pluck('total', 'level_teknisi'));
    
    // Warna untuk Grafik (Disesuaikan untuk Major/Minor)
    const colors = {
        'Baik': 'rgb(40, 167, 69)', // Success/Green
        'Rusak': 'rgb(220, 53, 69)', // Danger/Red
        'Perlu Perbaikan': 'rgb(255, 193, 7)', // Warning/Yellow
        'Terjadwal': 'rgb(255, 193, 7)',
        'Proses': 'rgb(0, 123, 255)',
        'Selesai': 'rgb(40, 167, 69)',
        'Gagal': 'rgb(108, 117, 125)',
        
        // Warna Komplain SLA
        'major': 'rgb(220, 53, 69)', // Merah (Urgent/3 Hari)
        'minor': 'rgb(255, 193, 7)', // Kuning (Warning/1 Hari)
        
        'default': 'rgb(0, 123, 255)' 
    };

    // Helper untuk mengubah angka bulan menjadi nama
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    const formatBulan = (monthNum, yearNum) => `${monthNames[monthNum - 1]}-${yearNum}`;


    // --- 1. GRAFIK KONDISI ASET (PIE) ---
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

    // --- 2. GRAFIK STATUS MAINTENANCE (BAR) ---
    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(maintenanceStatusData),
            datasets: [{
                label: "Jumlah Maintenance",
                data: Object.values(maintenanceStatusData),
                backgroundColor: Object.keys(maintenanceStatusData).map(key => colors[key] || colors['default']),
                borderColor: '#4e73df',
            }],
        },
        options: {
            legend: { display: false },
        }
    });
    
    // --- 3. GRAFIK KOMPLAIN SLA BREAKDOWN (PIE/DONUT) ---
    new Chart(document.getElementById('komplainSLAChart'), {
        type: 'doughnut', // Menggunakan Doughnut agar terlihat beda dari Aset
        data: {
            labels: Object.keys(komplainSLAStatusData).map(key => key.charAt(0).toUpperCase() + key.slice(1)), // Capitalize
            datasets: [{
                data: Object.values(komplainSLAStatusData),
                backgroundColor: Object.keys(komplainSLAStatusData).map(key => colors[key] || colors['default']),
                hoverBackgroundColor: Object.keys(komplainSLAStatusData).map(key => colors[key] || colors['default']),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
    });

    // --- 4. GRAFIK TREN PENGAJUAN (LINE) ---
    const trenLabels = pengajuanTrenData.map(item => formatBulan(item.bulan, item.tahun));
    const trenTotals = pengajuanTrenData.map(item => item.total);

    new Chart(document.getElementById('pengajuanTrenChart'), {
        type: 'line',
        data: {
            labels: trenLabels.reverse(), 
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