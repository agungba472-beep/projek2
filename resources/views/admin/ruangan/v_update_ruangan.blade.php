// File BARU: resources/views/admin/ruangan/v_update_ruangan.blade.php

@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-warning text-white">
            <h4 class="m-0">Edit Ruangan: {{ $ruangan->nama_ruangan }}</h4>
        </div>

        <form action="{{ route('ruangan.update', $ruangan->ruangan_id) }}" method="POST">
            @csrf
            {{-- Karena route menggunakan POST, kita gunakan @method('POST') atau @method('PUT') 
            jika kita menggunakan PUT/PATCH di route, namun rute Anda adalah POST --}}
            
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
                    <label class="form-label" for="nama_ruangan">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" 
                           value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kepala_lab_id">Kepala Lab Penanggung Jawab (Opsional)</label>
                    <select name="kepala_lab_id" id="kepala_lab_id" class="form-control">
                        <option value="">-- Tidak Ada Kepala Lab --</option>
                        {{-- $dosen dan $ruangan data sudah tersedia dari RuanganController::edit() --}}
                        @foreach($dosen as $d)
                            <option value="{{ $d->dosen_id }}" 
                                {{ old('kepala_lab_id', $ruangan->kepala_lab_id) == $d->dosen_id ? 'selected' : '' }}>
                                {{ $d->nama }} (NIDN: {{ $d->nidn ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('ruangan.index') }}" class="btn btn-secondary me-2">Kembali</a>
                <button type="submit" class="btn btn-warning">Perbarui</button>
            </div>
        </form>

    </div>
</div>
@endsection