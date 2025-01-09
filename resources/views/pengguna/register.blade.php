@extends('layouts.app')

@section('content')
<div class="container w-50 m-auto mt-5 bg-white p-4 rounded-3">
    <h3 class="text-center">Registrasi Pengguna</h3>

    <!-- Menampilkan pesan sukses jika ada -->
    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan pesan error jika ada -->
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pengguna.store') }}">
        @csrf
        <div class="mb-3">
            <label>No. KTP*</label>
            <input type="text" name="no_ktp" class="form-control" required maxlength="16" minlength="16" value="{{ old('no_ktp') }}">
        </div>
        <div class="mb-3">
            <label>Nama Lengkap*</label>
            <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
        </div>
        <div class="mb-3">
            <label>Alamat*</label>
            <textarea class="form-control" name="alamat" required>{{ old('alamat') }}</textarea>
        </div>
        <div class="mb-3">
            <label>No. HP*</label>
            <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp') }}">
        </div>
        <div class="mb-3">
            <label>No. NPWP</label>
            <input type="text" name="no_npwp" class="form-control" value="{{ old('no_npwp') }}">
        </div>
        <div class="mb-3">
            <label>No. Rekening</label>
            <input type="text" name="no_rekening" class="form-control" value="{{ old('no_rekening') }}">
        </div>
        <div class="mb-3">
            <label>Tarif/daya*</label>
            <input type="text" name="tarif_daya" class="form-control" required value="{{ old('tarif_daya') }}">
        </div>
        <div class="mb-3">
            <label>Email*</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Password*</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3 text-center">
            <button class="btn btn-primary">Sign Up</button>
        </div>
    </form>

    <a href="{{ route('home') }}" class="text-white position-absolute bottom-0 end-0 mb-3 me-3">
        <i class="fa fa-arrow-left"></i> back
    </a>
</div>
@endsection
