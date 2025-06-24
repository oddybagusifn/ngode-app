@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">

            <!-- Kolom Kiri: Gambar -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
                <div class="">
                    <!-- Gambar di sini -->
                    <div class="w-100">
                        <img src="img/loginBanner.svg" alt="" class="img-fluid"
                            style="max-height: 100vh; object-fit: contain;">
                    </div>
                </div>
            </div>


            <!-- Kolom Kanan: Form Login -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="w-75">
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <img class="img-fluid" src="img/brand.svg" alt="Logo Brand" style="max-height: 80px;">
                    </div>

                    <div class="text-center text-muted mb-4 ">
                        <p class="fw-bold fs-4 m-0">Daftar Akun Anda</p>
                        <p class="text-secondary">Pastikan data yang Anda masukkan sudah benar!</p>
                    </div>

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control p-2" id="name"
                                placeholder="Nama Lengkap" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control p-2" id="email"
                                placeholder="Alamat Email" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" name="password" class="form-control p-2" id="password"
                                placeholder="Kata Sandi" autocomplete="new-password">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
    <input type="password" name="password_confirmation" class="form-control p-2"
        id="password_confirmation" placeholder="Ulangi Kata Sandi" autocomplete="new-password">
    @error('password_confirmation')
        <p class="text-danger small mt-1">{{ $message }}</p>
    @enderror
</div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                            <a href="#" class="text-decoration-none small" style="color: #a85525">Lupa Kata
                                Sandi</a>
                        </div>
                        <div class="d-grid mb-3 ">
                            <button type="submit" class="btn text-white" style="background-color: #a85525;">Daftar</button>
                        </div>

                        <div class="text-center text-muted mb-3">atau lanjutkan dengan</div>
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('google.redirect') }}" type="button" class="btn btn-outline-dark w-50">
                                <img src="img/google.svg" alt=""> Google
                            </a>
                            <a href="#" type="button" class="btn btn-outline-dark w-50">
                                <img src="img/facebook.svg" alt=""> Facebook
                            </a>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">Belum Punya Akun? <a href="/login" class="text-decoration-none"
                                    style="color: #a85525">Masuk</a></small>
                        </div>
                    </form>

                    <p class="text-center text-muted mt-4 small">2025 Ngode. Hak cipta dilindungi undang-undang.</p>
                </div>
            </div>

        </div>
    </div>
@endsection
