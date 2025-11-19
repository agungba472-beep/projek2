@extends('layouts.v_template')

@section('content')

<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-warning text-white">
            <h4>Edit Aset</h4>
        </div>

        <form action="{{ route('aset.update', $aset->aset_id) }}" method="POST">

            @csrf

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="nama" class="form-control" value="{{ $aset->nama }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <input type="text" name="jenis" class="form-control" value="{{ $aset->jenis }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ $aset->lokasi }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Peroleh</label>
                    <input type="date" name="tanggal_peroleh" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Umur Maksimal (Tahun)</label>
                    <input type="number" name="umur_maksimal" class="form-control" min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nilai</label>
                    <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $aset->nilai }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-control">
                        <option {{ $aset->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option {{ $aset->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option {{ $aset->kondisi == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        <option {{ $aset->kondisi == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option {{ $aset->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option {{ $aset->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option {{ $aset->status == 'Diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                    </select>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="/admin/aset" class="btn btn-secondary me-2">Kembali</a>
                <button class="btn btn-warning">Update</button>
            </div>

        </form>
    </div>
</div>

@endsection
