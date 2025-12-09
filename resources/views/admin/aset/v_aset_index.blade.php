// File: resources/views/admin/aset/v_aset_index.blade.php

@extends('layouts.v_template')

@section('content')
<div class="container-fluid">
    <h3>Kelola Aset Tetap</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
            <a href="{{ route('aset.tambah') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Aset
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Aset</th>
                            <th>Jenis</th>
                            <th>Ruangan</th> <th>Tahun Pengadaan</th> <th>Tanggal Peroleh</th>
                            <th>Umur Maksimal</th>
                            <th>Nilai</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aset as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis ?? '-' }}</td>
                            <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td> 
                            <td>{{ $item->tahun_pengadaan ?? '-' }}</td> 
                            <td>{{ $item->tanggal_peroleh ?? '-' }}</td>
                            <td>{{ $item->umur_maksimal ?? '-' }}</td>
                            <td>{{ number_format($item->nilai, 2) ?? '-' }}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @if($item->qr_code)
                                    <a href="{{ route('qr.download', $item->aset_id) }}" class="btn btn-sm btn-info" target="_blank">
                                        Download QR
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('aset.edit', $item->aset_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('maintenance.listAset', $item->aset_id) }}" class="btn btn-info btn-sm">MT</a>
                                <form action="{{ route('aset.delete', $item->aset_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
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