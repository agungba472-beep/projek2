@extends('layouts.v_template')

@section('content')
<div class="container-fluid">
    <h3>Kelola Data Ruangan</h3>

    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Ruangan</h6>
            <a href="{{ route('ruangan.tambah') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Ruangan
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Kepala Lab</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_ruangan }}</td>
                            <td>
                                @if($item->kepalaLab)
                                    {{ $item->kepalaLab->nama ?? 'N/A' }} 
                                    <small class="text-muted">({{ $item->kepalaLab->user->username ?? 'N/A' }})</small>
                                @else
                                    <span class="text-danger">Belum Ditunjuk</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ruangan.edit', $item->ruangan_id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('ruangan.delete', $item->ruangan_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus ruangan ini? Semua aset yang terhubung harus dihapus/dipindahkan terlebih dahulu.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
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