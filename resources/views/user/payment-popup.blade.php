@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h3>Proses Pembayaran</h3>
        <p>Mohon tunggu, membuka halaman pembayaran...</p>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        window.onload = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log(result);
                    window.location.href = "{{ route('feedback.page') }}";
                },
                onPending: function(result) {
                    console.log(result);
                    alert('Menunggu pembayaran!');
                },
                onError: function(result) {
                    console.log(result);
                    alert('Pembayaran gagal!');
                }
            });
        };
    </script>
@endsection
