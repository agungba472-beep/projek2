@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-primary text-white">
            <h4 class="m-0">Tambah Aset Baru</h4>
        </div>

        <form action="{{ route('aset.store') }}" method="POST">
            @csrf
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <input type="text" name="jenis" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control">
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
                    <label class="form-label">Nilai Aset</label>
                    <input type="number" name="nilai" step="0.01" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-control">
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Hilang">Hilang</option>
                        <option value="Dipinjam">Dipinjam</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Aset</label>
                    <select name="status" class="form-control">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Diperbaiki">Diperbaiki</option>
                    </select>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="/admin/aset" class="btn btn-secondary me-2">Kembali</a>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>
@endsection
