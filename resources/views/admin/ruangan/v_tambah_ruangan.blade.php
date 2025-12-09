@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-primary text-white">
            <h4 class="m-0">Tambah Ruangan Baru</h4>
        </div>

        <form action="{{ route('ruangan.store') }}" method="POST">
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
                    <label class="form-label" for="nama_ruangan">Nama Ruangan (Contoh: Teori 1, Lab Jaringan)</label>
                    <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" value="{{ old('nama_ruangan') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kepala_lab_id">Kepala Lab Penanggung Jawab (Opsional)</label>
                    <select name="kepala_lab_id" id="kepala_lab_id" class="form-control">
                        <option value="">-- Tidak Ada Kepala Lab --</option>
                        {{-- $dosen diambil dari RuanganController::create() --}}
                        @foreach($dosen as $d)
                            <option value="{{ $d->dosen_id }}" {{ old('kepala_lab_id') == $d->dosen_id ? 'selected' : '' }}>
                                {{ $d->nama }} (NIDN: {{ $d->nidn ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('ruangan.index') }}" class="btn btn-secondary me-2">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>
@endsection