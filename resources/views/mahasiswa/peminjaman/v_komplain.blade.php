@extends('layouts.v_template')

@section('content')

<h3>Komplain Fasilitas / Ruangan</h3>

<form action="{{ route('komplain.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Kategori</label>
        <select name="kategori" class="form-control">
            <option value="ruangan">Ruangan</option>
            <option value="fasilitas">Fasilitas</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Deskripsi Komplain</label>
        <textarea name="deskripsi" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Kirim Komplain</button>
</form>

<hr>

<h4>Riwayat Komplain Saya</h4>
<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Deskripsi</th>
        <th>Status</th>
    </tr>

    @foreach($data as $k)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $k->kategori }}</td>
        <td>{{ $k->deskripsi }}</td>
        <td><span class="badge bg-info">{{ $k->status }}</span></td>
    </tr>
    @endforeach
</table>

@endsection
