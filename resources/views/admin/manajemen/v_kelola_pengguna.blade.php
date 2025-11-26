@extends('layouts.v_template')

@section('content')

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">
            <h4>Kelola Pengguna</h4>

            <!-- Tombol Tambah -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                Tambah Pengguna
            </button>
        </div>

        <div class="card-body">

            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Notifikasi Error Umum (Jika Ada) -->
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Tabel Pengguna -->
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Seen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($user as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ ucfirst($u->role) }}</td>

                        <td>
                            @if($u->status == 'aktif')
                                <span class="badge bg-success">Online</span>
                            @else
                                <span class="badge bg-secondary">Offline</span>
                            @endif
                        </td>

                        <td>{{ $u->last_seen ?? '-' }}</td>

                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $u->user_id }}">
                                Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus{{ $u->user_id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <!-- ========================= -->
                <!-- MODAL EDIT PENGGUNA       -->
                <!-- ========================= -->
                <div class="modal fade" id="modalEdit{{ $u->user_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="/admin/manajemen/kelola/update/{{ $u->user_id }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-content text-dark">

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pengguna - {{ $u->username }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="{{ $u->username }}">

                                    <label class="mt-3">Password (opsional)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">

                                    <label class="mt-3">Role</label>
                                    <select name="role" class="form-control">
                                        <option value="mahasiswa" {{ $u->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        <option value="dosen" {{ $u->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                        <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="teknisi" {{ $u->role == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                    </select>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


                <!-- ========================= -->
                <!-- MODAL HAPUS PENGGUNA      -->
                <!-- ========================= -->
                <div class="modal fade" id="modalHapus{{ $u->user_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="/admin/manajemen/kelola/delete/{{ $u->user_id }}">
                            @csrf
                            @method('DELETE')

                            <div class="modal-content text-dark">

                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Pengguna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <p>Yakin ingin menghapus <strong>{{ $u->username }}</strong>?</p>
                                    <p class="text-danger"><small>Aksi ini tidak dapat dibatalkan.</small></p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal Tambah (Perbaikan Validasi) -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="/admin/manajemen/kelola/store">
            @csrf

            <div class="modal-content text-dark">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <!-- AREA MENAMPILKAN ERROR VALIDASI -->
                    @if ($errors->any() && old('_token') && !$errors->has('user_id'))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- AKHIR AREA ERROR VALIDASI -->

                    <label>Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <label class="mt-3">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <label class="mt-3">Role</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>

            </div>

        </form>
    </div>
</div>

<!-- SCRIPT UNTUK MEMBUKA MODAL KEMBALI JIKA ADA ERROR VALIDASI -->
@if ($errors->any() && old('_token'))
<script>
    // Hanya jalankan jika ada error validasi dan form sudah pernah disubmit
    document.addEventListener('DOMContentLoaded', function() {
        var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
        modalTambah.show();
    });
</script>
@endif

@endsection