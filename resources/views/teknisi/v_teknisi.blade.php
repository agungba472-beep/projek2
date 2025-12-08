@extends('layouts.v_template')

@section('title', 'Dashboard Teknisi')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
    .card-dashboard {
        border-radius: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
        min-height: 120px; /* Tinggi minimum yang baik */
        cursor: pointer;
    }
    .card-dashboard:hover {
        transform: translateY(-5px); /* Efek kuat */
        box-shadow: 0 15px 30px rgba(0,0,0,.15);
    }
</style>

<div class="container-fluid py-4">
    <h3>Halo, {{ session('username') }} 👋</h3>
    <p>Ringkasan tugas perbaikan Komplain dan Maintenance yang ditugaskan kepada Anda.</p>
    
    <hr class="mb-5">
    
    {{-- ================================================= --}}
    {{-- A. SEKSI KOMPLAIN (PERBAIKAN) --}}
    {{-- ================================================= --}}
    <h4 class="mb-3 text-primary"><i class="fas fa-headset mr-2"></i> Status Komplain Saya</h4>
    <div class="row mb-4">
        
        {{-- 1. Komplain Aktif (ACTION REQUIRED - Primary) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            {{-- Mengarah ke daftar komplain aktif teknisi (Asumsi route: teknisi.komplain.index) --}}
            <a href="{{ route('teknisi.komplain.index') }}" style="text-decoration: none;"> 
                <div class="card bg-primary text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Komplain Baru/Diproses</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $komplainStats['active'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-tasks fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 2. SLA Warning (URGENT - Danger/Warning) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('teknisi.komplain.index') }}" style="text-decoration: none;"> 
                <div class="card bg-danger text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">SLA Warning / Deadline Dekat</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $komplainStats['slaWarning'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-exclamation-triangle fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 3. Total Komplain (Informational - Secondary) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('teknisi.komplain.index') }}" style="text-decoration: none;"> 
                <div class="card bg-secondary text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Riwayat Komplain Ditugaskan</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $komplainStats['totalAssigned'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-history fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <hr class="mb-5">

    {{-- ================================================= --}}
    {{-- B. SEKSI MAINTENANCE (JADWAL) --}}
    {{-- ================================================= --}}
    <h4 class="mb-3 text-success"><i class="fas fa-wrench mr-2"></i> Status Maintenance Saya</h4>
    <div class="row mb-4">
        
        {{-- 4. Maintenance Hari Ini (ACTION REQUIRED - Success) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            {{-- Mengarah ke daftar maintenance terjadwal/hari ini (Asumsi route: maintenance.index) --}}
            <a href="{{ route('maintenance.index') }}" style="text-decoration: none;"> 
                <div class="card bg-success text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Dijadwalkan Hari Ini</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $maintenanceStats['scheduledToday'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar-check fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 5. Maintenance Terlambat (CRITICAL - Danger) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('maintenance.index') }}" style="text-decoration: none;"> 
                <div class="card bg-danger text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Jadwal Terlambat (Overdue)</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $maintenanceStats['overdue'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clock fa-3x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 6. Maintenance Sedang Berjalan (Informational - Warning) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('maintenance.index') }}" style="text-decoration: none;"> 
                <div class="card bg-warning text-dark shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Sedang Dalam Proses</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $maintenanceStats['inProgress'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-spinner fa-3x fa-pulse"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <hr class="mb-5">
    
    {{-- C. VISUALISASI GRAFIK (Untuk Maintenance) --}}
    <h4 class="mb-3 text-secondary"><i class="fas fa-chart-pie mr-2"></i> Visualisasi Tugas</h4>
    <div class="row">
        {{-- Grafik Status Maintenance --}}
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pelaksanaan Maintenance Saya</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="maintenanceStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Placeholder untuk Grafik Komplain jika datanya lebih kompleks --}}
        <div class="col-xl-6 col-lg-6 mb-4">
             <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Komplain (Detail)</h6>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted pt-5">
                        <i class="fas fa-info-circle"></i> Grafik Komplain akan tampil di sini jika ada data yang kompleks. <br>
                        Saat ini fokus pada kartu metrik di atas.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT JAVASCRIPT UNTUK GRAFIK --}}
<script>
    // Data dari Controller
    const maintenanceSLAStatusData = @json($maintenanceSLAStatus->pluck('total', 'status')); 
    
    // Warna untuk Grafik
    const colors = {
        'Terjadwal': 'rgb(255, 193, 7)', // Warning
        'Proses': 'rgb(0, 123, 255)',    // Primary
        'Selesai': 'rgb(40, 167, 69)',   // Success
        'Gagal': 'rgb(220, 53, 69)',     // Danger
        'default': 'rgb(108, 117, 125)' 
    };

    // --- 1. GRAFIK STATUS MAINTENANCE ---
    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(maintenanceSLAStatusData),
            datasets: [{
                data: Object.values(maintenanceSLAStatusData),
                backgroundColor: Object.keys(maintenanceSLAStatusData).map(key => colors[key] || colors['default']),
                hoverBackgroundColor: Object.keys(maintenanceSLAStatusData).map(key => colors[key] || colors['default']),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                align: 'start',
            },
        }
    });
</script>
@endsection