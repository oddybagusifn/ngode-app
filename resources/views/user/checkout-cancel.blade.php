@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h3>Transaksi Dibatalkan</h3>
        <p>Anda membatalkan proses pembayaran. Silakan coba lagi.</p>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-3">Kembali ke Checkout</a>
    </div>
@endsection
