@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h3>Transaksi Gagal</h3>
        <p>Terjadi kesalahan saat memproses pembayaran Anda.</p>
        <a href="{{ route('checkout.index') }}" class="btn btn-danger mt-3">Coba Lagi</a>
    </div>
@endsection
