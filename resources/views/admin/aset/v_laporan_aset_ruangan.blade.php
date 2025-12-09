@extends('layouts.v_template')

@section('content')
<div class="container-fluid">
    <h3>Laporan Aset per Ruangan dan Kondisi</h3>

    <div class="card p-3 mb-4 shadow">
        <h5 class="mb-3">Filter Data Aset</h5>
        <form action="{{ route('laporan.aset') }}" method="GET">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ruangan_id" class="form-label">Filter Ruangan:</label>
                    <select name="ruangan_id" id="ruangan_id" class="form-control">
                        <option value="">-- Semua Ruangan --</option>
                        @foreach($ruanganList as $ruangan)
                            <option value="{{ $ruangan->ruangan_id }}" {{ request('ruangan_id') == $ruangan->ruangan_id ? 'selected' : '' }}>
                                {{ $ruangan->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="kondisi" class="form-label">Filter Kondisi/Kerusakan:</label>
                    <select name="kondisi" id="kondisi" class="form-control">
                        <option value="">-- Semua Kondisi --</option>
                        @foreach($kondisiList as $kondisi)
                            <option value="{{ $kondisi }}" {{ request('kondisi') == $kondisi ? 'selected' : '' }}>
                                {{ $kondisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan Laporan</button>
                    <a href="{{ route('laporan.aset') }}" class="btn btn-secondary">Reset Filter</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Aset</th>
                            <th>Ruangan</th>
                            <th>Kepala Lab</th>
                            <th>Tahun Pengadaan</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aset as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                            <td>{{ $item->ruangan->kepalaLab->nama ?? '-' }}</td> 
                            <td>{{ $item->tahun_pengadaan ?? '-' }}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection