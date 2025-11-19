@extends('layouts.v_template')

@section('title')
Dashboard SB admin

@endsection
@section('content')
        <body class="p-4">
            <h3>Halo, {{ session('username') }} 👋</h3>
            <p>Selamat datang di dashboard admin.</p>
        </body>
@endsection