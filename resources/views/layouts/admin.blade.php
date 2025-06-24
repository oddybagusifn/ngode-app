@extends('layouts.app') {{-- extend layout utama --}}

@section('content')
    {{-- SESUAI DENGAN app.blade.php --}}
    <x-admin-navbar></x-admin-navbar>

    <div class="beranda mt-5">
        <div class="header d-flex align-items-center justify-content-between">
            <p class="fw-medium fs-2 ms-5 p-2">
                @yield('page-title', 'Beranda')
            </p>
            @if(View::hasSection('is-dashboard'))
    <div class="ms-auto gap-4">
        <button class="btn me-2" style="border: 1px solid #F5CCA0;border-radius:12px;">
            Unduh Laporan (.pdf)
        </button>
        <button class="btn" style="border: 1px solid #F5CCA0;border-radius:12px;">
            Unduh Laporan (.xlsx)
        </button>
    </div>
@endif

        </div>

        <div class="body w-100 mt-4 d-flex" style="min-height:500px">
            <x-admin-sidebar />
            <div class="data-main w-100 bg-light rounded-3 p-4" style="min-height:500px">
                <div class="data-body rounded-3 p-4" style="background-color: #ffff; min-height:100%">
                    @yield('admin-contents') {{-- ini akan diisi oleh dashboard --}}
                </div>
            </div>
        </div>
    </div>

    <x-footer />
@endsection
