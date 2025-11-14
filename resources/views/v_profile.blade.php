@extends('layouts.v_template')

@section('content')

<style>
    .profil-wrapper {
        max-width: 420px;
        margin: 40px auto;
        background: white;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }

    .profil-header {
        background: #0d6efd;
        color: white;
        padding: 12px 20px;
        font-size: 22px;
        font-weight: bold;
        border-radius: 12px 12px 0 0;
        margin: -25px -25px 20px -25px;
    }

    .profil-image {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: #d7d7d7;
        margin: 15px auto;
        display: block;
    }

    .btn-area {
        margin-top: 20px;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .btn-save { background: #001f3f; color: white; }
    .btn-edit { background: #28a745; color: white; }
    .btn-cancel { background: #dc3545; color: white; }
</style>

<div class="container d-flex justify-content-center align-items-start mt-5">
    <div class="card shadow" style="width: 500px; border-radius: 15px;">
        
        <div class="card-header text-white" style="background:#007bff; border-top-left-radius:15px; border-top-right-radius:15px;">
            <h4>Profil</h4>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
    @csrf


            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" 
                        name="nama" value="{{ $user->nama ?? '' }}" placeholder="Masukkan nama">

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input type="text" class="form-control" 
                        name="nim" value="{{ $user->nim ?? '' }}" placeholder="Masukkan NIM">
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input type="text" class="form-control" 
       name="kelas" value="{{ $user->kelas ?? '' }}" placeholder="Masukkan kelas">
            </div>

            <div class="text-center my-3">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
                     width="120" class="img-fluid">
            </div>

            <div class="text-center">
                <button class="btn btn-dark mx-1">Save</button>
                <button class="btn btn-success mx-1">Edit</button>
                <button class="btn btn-danger mx-1">Cancel</button>
            </div>

        </form>


    </div>
</div>


@endsection
