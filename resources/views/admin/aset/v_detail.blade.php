@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Detail Laporan Aset</h3>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Aset</strong>
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $aset->nama }}</p>
            <p><strong>Jenis:</strong> {{ $aset->jenis }}</p>
            <p><strong>Tanggal Peroleh:</strong> {{ $aset->tanggal_peroleh ?? '-' }}</p>
            <p><strong>Umur Maksimal:</strong> {{ $aset->umur_maksimal ? $aset->umur_maksimal.' Tahun' : '-' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Pemutihan Aset</strong>
        </div>

        <div class="card-body">
            @if ($pemutihan)
            <table class="table table-bordered">
                <tr>
                    <th>Nama Aset</th>
                    <td>{{ $pemutihan['nama'] }}</td>
                </tr>
                <tr>
                    <th>Jenis</th>
                    <td>{{ $pemutihan['jenis'] }}</td>
                </tr>
                <tr>
                    <th>Tanggal Peroleh</th>
                    <td>{{ $pemutihan['tanggal_peroleh'] }}</td>
                </tr>
                <tr>
                    <th>Umur Maksimal</th>
                    <td>{{ $pemutihan['umur_maksimal'] }} Tahun</td>
                </tr>
                <tr>
                    <th>Tanggal Kadaluarsa</th>
                    <td>{{ $pemutihan['tanggal_kadaluarsa'] }}</td>
                </tr>
                <tr class="table-danger">
                    <th>Status</th>
                    <td><strong>Aset Harus Dimutihkan</strong></td>
                </tr>
            </table>

            @else
            <p class="text-success"><strong>Tidak ada pemutihan untuk aset ini 🎉</strong></p>
            @endif
        </div>
    </div>

    {{-- Tombol Download --}}
   <div class="d-flex justify-content-end">
    <a href="/admin/laporan" class="btn btn-secondary me-2">Kembali</a>
    <a href="{{ route('laporan.inventaris.excel', $aset->aset_id) }}" class="btn btn-success">
        Download Excel
    </a>
    </div>

</div>
@endsection
