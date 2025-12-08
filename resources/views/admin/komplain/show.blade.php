@extends('layouts.v_template')

@section('content')

<h3>Detail Komplain</h3>

<div class="card p-4">
    {{-- INFORMASI KOMPLAIN --}}
    <p><b>User:</b> {{ $komplain->user->username }}</p> {{-- Catatan: Menggunakan username (sesuai Model User) --}}
    <p><b>Kategori:</b> {{ $komplain->kategori }}</p>
    <p><b>Deskripsi:</b> {{ $komplain->deskripsi }}</p>
    <p><b>Status:</b> <span class="badge bg-info">{{ $komplain->status }}</span></p>

    <hr>

    {{-- FORM UNTUK UPDATE KOMPLAIN --}}
    <form action="{{ route('admin.komplain.update', $komplain->komplain_id) }}" method="POST">
        @csrf 
        @method('PUT') {{-- KOREKSI UTAMA: Menggunakan PUT untuk operasi UPDATE --}}

        {{-- BAGIAN 1: PEMILIHAN TEKNISI --}}
        <div class="mb-3">
            <label>Pilih Teknisi</label>
            <select name="assigned_to" class="form-control">
                {{-- Opsi Default --}}
                <option value="">-- Belum Ditugaskan --</option>
                
                {{-- Loop untuk Daftar Teknisi --}}
                @foreach($teknisi as $t)
                    {{-- KRITIS: Menggunakan $t->user_id (primary key Model User) --}}
                    <option value="{{ $t->user_id }}" {{ $komplain->assigned_to == $t->user_id ? 'selected' : '' }}>
                        {{ $t->username }} {{-- Menampilkan username --}}
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- BAGIAN 2: LEVEL KERUSAKAN --}}
        <div class="mb-3">
            <label>Level Kerusakan (Admin)</label>
            <select name="level_admin" class="form-control">
                <option value="">-- Belum Ditentukan --</option>
                <option value="minor" {{ $komplain->level_admin == 'minor' ? 'selected' : '' }}>Minor(SLA 1 hari)</option>
                <option value="major" {{ $komplain->level_admin == 'major' ? 'selected' : '' }}>Major(SLA 3 hari)</option>
            </select>
            @error('level_admin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

@endsection