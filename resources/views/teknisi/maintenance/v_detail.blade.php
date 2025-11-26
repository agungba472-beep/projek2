@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h4>Detail Maintenance</h4>
            {{-- TOMBOL KEMBALI DINAMIS --}}
@if(Auth::user()->role == 'Admin')
    <a href="{{ route('aset.index', $maintenance->aset_id) }}" 
       class="btn btn-secondary">
        Kembali
    </a>
@elseif(Auth::user()->role == 'Teknisi')
    <a href="{{ route('maintenance.index') }}" 
       class="btn btn-secondary">
        Kembali
    </a>
@else
    <a href="javascript:history.back()" class="btn btn-secondary">
        Kembali
    </a>
@endif
        </div>

        <div class="card-body">
            
            <div class="mb-3">
                <strong>Aset:</strong> {{ $maintenance->aset->nama ?? '-' }}<br>
                <strong>Jenis:</strong> {{ $maintenance->jenis }}<br>
                <strong>Tanggal Dijadwalkan:</strong> {{ $maintenance->tanggal_dijadwalkan }}<br>
                <strong>Status:</strong> {{ $maintenance->status }}<br>
                <strong>Teknisi:</strong> {{ $maintenance->teknisi->username ?? '-' }}<br>
                <strong>Catatan:</strong> {{ $maintenance->catatan ?? '-' }}
            </div>

            <hr>

            <h5>Detail Pekerjaan</h5>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahDetailModal">
                + Tambah Detail
            </button>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Kondisi Sebelum</th>
                        <th>Kondisi Sesudah</th>
                        <th>Tindakan</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenance->details as $d)
                    <tr>
                        <td>{{ $d->deskripsi }}</td>
                        <td>{{ $d->kondisi_sebelum }}</td>
                        <td>{{ $d->kondisi_sesudah }}</td>
                        <td>{{ $d->tindakan }}</td>
                        <td>
                            @if($d->foto)
                            <a href="{{ asset('storage/'.$d->foto) }}" target="_blank">Lihat Foto</a>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada detail maintenance</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Detail -->
<div class="modal fade" id="tambahDetailModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('maintenance.detail.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="maintenance_id" value="{{ $maintenance->maintenance_id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Detail Maintenance</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Kondisi Sebelum</label>
                        <input type="text" name="kondisi_sebelum" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Kondisi Sesudah</label>
                        <input type="text" name="kondisi_sesudah" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Tindakan</label>
                        <input type="text" name="tindakan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
