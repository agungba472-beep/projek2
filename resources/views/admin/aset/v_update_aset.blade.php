@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-warning text-white">
            <h4 class="m-0">Edit Aset: {{ $aset->nama }}</h4>
        </div>

        <form action="{{ route('aset.update', $aset->aset_id) }}" method="POST">
            @csrf
            <div class="card-body">

                {{-- Notifikasi Error --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $aset->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <input type="text" name="jenis" class="form-control" value="{{ old('jenis', $aset->jenis) }}">
                </div>

                <div class="mb-3">
                    <label for="ruangan_id" class="form-label">Ruangan / Lokasi</label>
                    <select name="ruangan_id" id="ruangan_id" class="form-control" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $item)
                            <option value="{{ $item->ruangan_id }}" 
                                {{ old('ruangan_id', $aset->ruangan_id) == $item->ruangan_id ? 'selected' : '' }}>
                                {{ $item->nama_ruangan }}
                                @if($item->kepalaLab)
                                    ({{ $item->kepalaLab->nama ?? '-' }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tahun_pengadaan" class="form-label">Tahun Pengadaan</label>
                    <input type="number" name="tahun_pengadaan" id="tahun_pengadaan" class="form-control" 
                           placeholder="Contoh: 2024" min="1900" max="{{ date('Y') }}" 
                           value="{{ old('tahun_pengadaan', $aset->tahun_pengadaan) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Peroleh</label>
                    <input type="date" name="tanggal_peroleh" class="form-control" value="{{ old('tanggal_peroleh', $aset->tanggal_peroleh) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Umur Maksimal (Tahun)</label>
                    <input type="number" name="umur_maksimal" class="form-control" min="1" value="{{ old('umur_maksimal', $aset->umur_maksimal) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nilai Aset</label>
                    <input type="number" name="nilai" step="0.01" class="form-control" value="{{ old('nilai', $aset->nilai) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-control">
                        @foreach(['Baik', 'Rusak', 'Hilang', 'Dipinjam'] as $kondisi)
                            <option value="{{ $kondisi }}" {{ old('kondisi', $aset->kondisi) == $kondisi ? 'selected' : '' }}>
                                {{ $kondisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Aset</label>
                    <select name="status" class="form-control">
                        @foreach(['Tersedia', 'Dipinjam', 'Diperbaiki'] as $status)
                            <option value="{{ $status }}" {{ old('status', $aset->status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('aset.index') }}" class="btn btn-secondary me-2">Kembali</a>
                <button class="btn btn-warning">Perbarui</button>
            </div>
        </form>

    </div>
</div>
@endsection