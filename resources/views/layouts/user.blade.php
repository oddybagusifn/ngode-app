@extends('layouts.app')

@section('content')
    <x-navbar />
    <div class="">
        @yield('user-content')

    </div>
    <x-footer />
    <x-cart-sidebar />
@endsection
