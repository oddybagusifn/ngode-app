@extends('layouts.user')

@section('user-content')
    <div class="container-fluid py-5">
        <h4 class="text-body-secondary fw-semibold">PRODUK</h4>
        <div class="product row flex-row-reverse justify-content-end">
            @foreach ($products as $product)
                <div class="col-12 col-md-6 col-lg-3 g-4 d-flex">
                    <a href="{{route('homepage.product', $product->id)}}" class="w-100 text-decoration-none">
                        <div class="card h-100 d-flex flex-column border-0">
                            <div style="height: 300px; overflow: hidden;">
                                <img src="{{ asset($product->image) }}" alt="Gambar Produk" class="img-fluid w-100 h-100"
                                    style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between rounded-3 rounded-top-0"
                                style="border: 1px solid #F5CCA0; border-top: none;">
                                <p class="card-text fw-semibold">{{ $product->name }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rating d-flex">
                                        @for ($i = 0; $i < 4; $i++)
                                            <p class="card-text fw-semibold mb-0">
                                                <img class="img-fluid" src="img/star.svg" alt="star">
                                            </p>
                                        @endfor
                                        <p class="card-text fw-semibold mb-0">
                                            <img class="img-fluid" src="img/star-disabled.svg" alt="star-disabled">
                                        </p>
                                    </div>
                                    <div class="review">
                                        <p class="text-secondary mt-1 mb-0">42 Ulasan</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                    <p class="fw-semibold fs-5 mb-0">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    <form action="">
                                        <button type="submit" class="btn border-0 p-0">
                                            <img src="img/addProduct.svg" alt="add">
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
@endsection
