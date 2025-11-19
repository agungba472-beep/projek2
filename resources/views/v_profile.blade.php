@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="m-0">Profil Pengguna</h4>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>

        <div class="card-body text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
            width="120" class="rounded-circle shadow-sm mb-3">

            <h5 class="fw-bold">{{ $profile->nama ?? '-' }}</h5>
            <p class="text-muted">{{ $user->role ?? '-' }}</p>

            <hr>

            <div class="text-start px-2">
                <p><strong>Username:</strong> {{ $user->username }}</p>

                {{-- Mahasiswa --}}
                @if($user->role === 'Mahasiswa')
                    <p><strong>NIM:</strong> {{ $profile->nim ?? '-' }}</p>
                    <p><strong>Kelas:</strong> {{ $profile->kelas ?? '-' }}</p>
                @endif

                {{-- Dosen --}}
                @if($user->role === 'Dosen')
                    <p><strong>NIDN:</strong> {{ $profile->nidn ?? '-' }}</p>
                    <p><strong>Prodi:</strong> {{ $profile->prodi ?? '-' }}</p>
                @endif

                {{-- Teknisi --}}
                @if($user->role === 'Teknisi')
                    <p><strong>Bidang:</strong> {{ $profile->bidang ?? '-' }}</p>
                @endif

                {{-- Admin --}}
                @if($user->role === 'Admin')
                    <p><strong>Jabatan:</strong> {{ $profile->jabatan ?? '-' }}</p>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- Modal Edit --}}
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input name="nama" class="form-control"
                        value="{{ old('nama', $profile->nama ?? '') }}" required>
                    </div>

                    {{-- Mahasiswa --}}
                    @if($user->role === 'Mahasiswa')
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input name="nim" class="form-control"
                            value="{{ old('nim', $profile->nim ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input name="kelas" class="form-control"
                            value="{{ old('kelas', $profile->kelas ?? '') }}" required>
                        </div>
                    @endif

                    {{-- Dosen --}}
                    @if($user->role === 'Dosen')
                        <div class="mb-3">
                            <label class="form-label">NIDN</label>
                            <input name="nidn" class="form-control"
                            value="{{ old('nidn', $profile->nidn ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prodi</label>
                            <input name="prodi" class="form-control"
                            value="{{ old('prodi', $profile->prodi ?? '') }}" required>
                        </div>
                    @endif

                    {{-- Teknisi --}}
                    @if($user->role === 'Teknisi')
                        <div class="mb-3">
                            <label class="form-label">Bidang</label>
                            <input name="bidang" class="form-control"
                            value="{{ old('bidang', $profile->bidang ?? '') }}" required>
                        </div>
                    @endif

                    {{-- Admin --}}
                    @if($user->role === 'Admin')
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input name="jabatan" class="form-control"
                            value="{{ old('jabatan', $profile->jabatan ?? '') }}" required>
                        </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script>alert('{{ session('success') }}');</script>
@endif
@endsection
