@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h3 class="text-success">Pembayaran Berhasil</h3>
    <p>Terima kasih, pesanan Anda telah kami terima.</p>
    <a href="{{ route('feedback.page') }}" class="btn btn-primary">Lanjut Beri Feedback</a>
    <a href="{{ route('feedback.page') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
