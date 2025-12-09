// File: resources/views/dosen/v_dosen.blade.php (KODE YANG DIKOREKSI)

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
</style>

<div class="container-fluid py-4">
    <h3>Halo, {{ session('username') }} 👋</h3>
    <p>Selamat datang di **dashboard Dosen JTIK**. Berikut ringkasan pengawasan aset dan laporan kerusakan.</p>
    
    <hr class="mb-5">
    
    {{-- A. KARTU PENGAWASAN ASET (Dosen Oversight) --}}
    <h4 class="mb-3 text-primary"><i class="fas fa-eye mr-2"></i> Pengawasan Aset</h4>
    <div class="row mb-5">
        
        {{-- 1. Jumlah Ruangan yang Diampu --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-info text-white shadow card-dashboard">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Ruangan Diampu Sebagai Kepala Lab</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['jumlahRuanganDiampu'] }} Ruangan</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chalkboard fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 2. Aset Rusak di Ruangan yang Diampu --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('dosen.aset.index') }}?kondisi=Rusak" style="text-decoration: none;"> 
                <div class="card bg-danger text-white shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Aset Rusak di Ruangan yang Diampu</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['asetRusakDiampu'] }} Unit</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        {{-- 3. Total Komplain Menunggu Proses --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('dosen.komplain.index') }}" style="text-decoration: none;"> 
                <div class="card bg-warning text-dark shadow card-dashboard">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Komplain Menunggu Tindakan</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $stats['totalKomplainMenunggu'] }} Laporan</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-headset fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <hr class="mb-5">

    {{-- B. Tautan Akses Utama --}}
    <h4 class="mb-4 text-secondary"><i class="fas fa-link mr-2"></i> Akses Cepat</h4>
    
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
            <a href="{{ route('dosen.aset.index') }}" class="btn btn-primary btn-lg btn-block shadow py-3">
                <i class="fas fa-boxes mr-2"></i> Lihat Daftar Aset JTIK Lengkap (Revisi #8)
            </a>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
            <a href="{{ route('dosen.komplain.index') }}" class="btn btn-secondary btn-lg btn-block shadow py-3">
                <i class="fas fa-wrench mr-2"></i> Ajukan Komplain Kerusakan
            </a>
        </div>
    </div>
    
</div>
@endsection