@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    {{-- Kartu Utama Detail Komplain --}}
    <div class="card shadow mb-4">
        {{-- Header Card (Informasi Utama) --}}
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Detail Komplain</h4>
        </div>
        
        <div class="card-body">
            
            {{-- Bagian Informasi Komplain (Menggunakan Grid/Row) --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>User Pelapor:</strong> {{ $k->user->username }}</p>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $k->kategori }}</p>
                </div>
                <div class="col-md-6">
                    {{-- Status dibuat menjadi badge --}}
                    <p class="mb-1">
                        <strong>Status Saat Ini:</strong> 
                        <span class="badge 
                            @if($k->status=='diproses') bg-primary 
                            @elseif($k->status=='selesai') bg-success 
                            @else bg-secondary 
                            @endif">
                            {{ ucfirst($k->status) }}
                        </span>
                    </p>
                    {{-- Level teknisi yang sudah dikonfirmasi --}}
                    <p class="mb-1">
                        <strong>Level Kerusakan (Konfirmasi):</strong> 
                        <span class="badge 
                            @if($k->level_teknisi=='major') bg-danger
                            @elseif($k->level_teknisi=='minor') bg-warning text-dark
                            @else bg-secondary
                            @endif">
                            {{ $k->level_teknisi ? ucfirst($k->level_teknisi) : 'Belum Dikonfirmasi' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <hr>

            <h5>Deskripsi Masalah</h5>
            <p class="alert alert-light border">{{ $k->deskripsi }}</p>

            <!-- @if ($k->bukti_foto)
                <h5>Bukti Foto Komplain</h5>
                <div>
                    <img src="/bukti/{{ $k->bukti_foto }}" alt="Bukti Foto Komplain" style="max-width: 200px; border-radius: 5px;" class="img-thumbnail">
                </div>
            @endif -->
        </div>
    </div>

    {{-- Kartu Form Update oleh Teknisi --}}
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Form Pembaruan & Tindak Lanjut</h5>
        </div>
        <div class="card-body">
            {{-- Form Aksi Update --}}
            <form action="{{ route('teknisi.komplain.update', $k->komplain_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Baris 1: Level dan Status (Menggunakan Grid) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Level Kerusakan</label>
                        {{-- Logika Blade TETAP SAMA --}}
                        <select name="level_teknisi" class="form-select">
                            <option value="">-- Pilih Level --</option>
                            <option value="minor" {{ $k->level_teknisi=='minor'?'selected':'' }}>Minor</option>
                            <option value="major" {{ $k->level_teknisi=='major'?'selected':'' }}>Major</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ubah Status Komplain</label>
                        {{-- Logika Blade TETAP SAMA --}}
                        <select name="status" class="form-select">
                            <option value="diproses" {{ $k->status=='diproses'?'selected':'' }}>Sedang Diproses</option>
                            <option value="selesai" {{ $k->status=='selesai'?'selected':'' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                
                {{-- Baris 2: Catatan Teknisi --}}
                <div class="mb-3">
                    <label class="form-label">Catatan Teknisi</label>
                    {{-- Logika Blade TETAP SAMA --}}
                    <textarea name="catatan_teknisi" class="form-control" rows="3">{{ $k->catatan_teknisi }}</textarea>
                </div>

                {{-- Baris 3: Bukti Foto --}}
                <div class="mb-3">
                    <label class="form-label">Bukti Foto (Opsional)</label>
                    {{-- Logika Blade TETAP SAMA --}}
                    <input type="file" name="bukti_foto" class="form-control">
                    
                    @if ($k->bukti_foto)
                        <small class="form-text text-muted mt-2 d-block">Bukti foto sudah tersedia. Unggah file baru untuk mengganti.</small>
                        <img src="/bukti/{{ $k->bukti_foto }}" width="150" class="mt-2 img-thumbnail">
                    @endif
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection