@extends('layouts.user')

@section('user-content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#" style="text-decoration: none" class="text-dark fw-medium fs-6">
                        {{ $products->category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#" style="text-decoration: none" class="text-dark fw-medium fs-6">
                        {{ $products->name }}
                    </a>
                </li>
            </ol>
        </nav>

        <div class="row" style="min-height: 600px;">
            <!-- Gambar Utama & Thumbnail -->
            <div class="col-lg-6 d-flex flex-column align-items-start justify-content-start h-100 mb-2">
                <!-- Gambar Utama -->
                <div class="flex-grow-1 w-100 d-flex bg-light border rounded-3 mb-3" style="height: 550px;">
                    <img src="{{ asset($products->image) }}" class="w-100 h-100 rounded-3"
                        style="object-fit: contain;" alt="Gambar Produk">
                </div>



                <!-- Thumbnails -->
                <div class="w-100 d-flex justify-content-start">
                    <div class="row row-cols-3 g-3" style="max-width: 100%;">
                        <div class="col d-flex justify-content-center">
                            <img src="{{ asset($products->image) }}" class="img-fluid rounded"
                                style="border: 2px solid #994D1C; max-width: 120px; cursor: pointer;" alt="Thumb 1">
                        </div>
                        <div class="col d-flex justify-content-center">
                            <img src="{{ asset($products->image) }}" class="img-fluid rounded border"
                                style="max-width: 120px; cursor: pointer;" alt="Thumb 2">
                        </div>
                        <div class="col d-flex justify-content-center">
                            <img src="{{ asset($products->image) }}" class="img-fluid rounded border"
                                style="max-width: 120px; cursor: pointer;" alt="Thumb 3">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Detail Produk -->
            <div class="col-lg-6">
                <!-- Nama & Rating -->
                <div class="mb-5">
                    <h6 class="text-muted">Maju Mundur Keramik</h6>
                    <h3>{{ $products->name }}</h3>

                    <!-- Rating -->
                    <div class="d-flex align-items-center mt-2">
                        <div class="me-2 text-warning">
                            @for ($i = 0; $i < 5; $i++)
                                <img src="/img/star.svg" class="img-fluid" alt="⭐">
                            @endfor
                        </div>
                        <div class="text-muted">42 Ulasan</div>
                    </div>
                </div>

                <h4 class="mb-4 fs-3">Rp{{ number_format($products->price, 0, ',', '.') }}</h4>
                <!-- Harga & Variasi -->
                <div class="mb-4 mt-5">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="m-0 fw-semibold">Variasi</p>
                        <div class="d-flex gap-3">
                            <button class="btn border rounded-3 text-nowrap" style="color: #994D1C;">Tampilan 3D</button>
                            <button class="btn border rounded-3 text-nowrap" style="color: #994D1C;">Coba Sekarang</button>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <img src="{{ asset($products->image) }}" class="img-thumbnail1 rounded-3"
                            style="width: 110px; border: 2px solid #994D1C;" alt="Variasi 1">
                        <img src="{{ asset($products->image) }}" class="img-thumbnail1 rounded-3 border"
                            style="width: 110px;" alt="Variasi 2">
                        <img src="{{ asset($products->image) }}" class="img-thumbnail1 rounded-3 border"
                            style="width: 110px;" alt="Variasi 3">
                    </div>
                </div>

                <!-- Ukuran -->
                <div class="mb-5 pb-5">
                    <div class="d-flex justify-content-between ">
                        <p class="fw-semibold">Ukuran</p>
                        <a href="#" class="text-secondary text-decoration-none">
                            <img src="/img/massage.svg" class="img-fluid" alt="Ukuran"> Petunjuk Ukuran
                        </a>
                    </div>
                    <div class="btn-group gap-4" role="group">
                        <button type="button" class="btn bg-light rounded-pill px-4 py-2">Besar</button>
                        <button type="button" class="btn bg-light rounded-pill px-4 py-2">Sedang</button>
                        <button type="button" class="btn bg-light rounded-pill px-4 py-2">Kecil</button>
                    </div>
                </div>

                <!-- Tombol dan Info Ongkir -->
                <div class="mb-5">
                    <div class="d-flex align-items-center">
                        <button
                            class="btn px-4 me-3 text-light rounded-pill py-2 fw-medium w-75 d-flex justify-content-center align-items-center"
                            style="background-color: #994D1C;">
                            <span class="m-0">Masukkan Keranjang</span>
                            <img src="/img/bag.svg" class="ms-2" alt="Keranjang">
                        </button>
                        <div class="d-flex">
                            <a href="#" class="btn p-2">
                                <img src="/img/chat.svg" width="60" alt="Chat">
                            </a>
                            <a href="#" class="btn p-2">
                                <img src="/img/love.svg" width="60" alt="Wishlist">
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <img src="/img/truck-fast.svg" alt="Truck" class="img-fluid">
                        <p class="fw-medium m-0">Gratis ongkir untuk pembelian minimal Rp200.000</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="other">
            <div class="d-flex gap-3 my-5">
                <p class="fw-medium" style="color: #994D1C">Detail</p>
                <p class="fw-medium">Ulasan</p>
            </div>
            <div class="row g-5">
                <div class="col-md-8">
                    <div class="">
                        <h5>Detail Produk</h5>
                        <p>{{ $products->description }}</p>
                    </div>
                    <div class="">
                        <h5>Keunggulan</h5>
                        <p>{{ $products->description }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <a href="#" class="">
                        <img src="/img/banner.svg" width="400px" class="img-fluid" alt="Banner">
                    </a>
                </div>
            </div>

        </div>

        <div class="recomended-product mt-5 pt-5 ">
            <div class="d-flex justify-content-between p-0">
                <h4 class="text-body-tertiary">PRODUK SERUPA</h4>
                <a href="{{route('homepage')}}" class="fw-light" style="color: #994D1C;text-decoration: none">
                    Selengkapnya
                </a>
            </div>
            <div class="product row flex-row-reverse justify-content-end">
                @foreach ($recomendedProducts as $product)
                    <div class="col-12 col-md-6 col-lg-3 g-4 d-flex">
                        <a href="{{ route('homepage.product', $product->id) }}" class="w-100 text-decoration-none">
                            <div class="card h-100 d-flex flex-column border-0">
                                <div style="height: 300px; overflow: hidden;">
                                    <img src="{{ asset($product->image) }}" alt="Gambar Produk"
                                        class="img-fluid w-100 h-100 rounded-top-3" style="object-fit: cover;">
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between rounded-3 rounded-top-0"
                                    style="border: 1px solid #F5CCA0; border-top: none;">
                                    <p class="card-text fw-semibold">{{ $product->name }}</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rating d-flex">
                                            @for ($i = 0; $i < 4; $i++)
                                                <p class="card-text fw-semibold mb-0">
                                                    <img class="img-fluid" src="/img/star.svg" alt="star">
                                                </p>
                                            @endfor
                                            <p class="card-text fw-semibold mb-0">
                                                <img class="img-fluid" src="/img/star-disabled.svg" alt="star-disabled">
                                            </p>
                                        </div>
                                        <div class="review">
                                            <p class="text-secondary mt-1 mb-0">42 Ulasan</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                        <p class="fw-semibold fs-5 mb-0">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        <form action="">
                                            <button type="submit" class="btn border-0 p-0">
                                                <img src="/img/addProduct.svg" alt="add">
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
