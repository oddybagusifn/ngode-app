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
                    <img id="mainImage" src="{{ $products->image ? asset($products->image) : asset('img/no-image.png') }}"
                        class="w-100 h-100 rounded-3" style="object-fit: contain;" alt="Gambar Produk">

                </div>

                <!-- Thumbnails -->
                <div class="w-100">
                    <div class="row row-cols-auto flex-nowrap overflow-auto" style="max-width: 100%; gap: 0;">
                        <div class="col flex-shrink-0">
                            <img src="{{ asset($products->image) }}"
                                class="img-fluid rounded border thumbnail-img active-thumb"
                                data-img="{{ asset($products->image) }}" style="max-width: 120px; cursor: pointer;"
                                alt="Thumbnail Utama">
                        </div>

                        @foreach ($products->images as $image)
                            <div class="col flex-shrink-0">
                                <img src="{{ asset($image->image_path) }}" class="img-fluid rounded border thumbnail-img"
                                    data-img="{{ asset($image->image_path) }}" style="max-width: 120px; cursor: pointer;"
                                    alt="Thumbnail Produk">
                            </div>
                        @endforeach
                    </div>
                </div>

                @push('scripts')
                    <script>
                        document.querySelectorAll('.thumbnail-img').forEach(thumbnail => {
                            thumbnail.addEventListener('click', function() {
                                const newSrc = this.getAttribute('data-img');
                                document.getElementById('mainImage').setAttribute('src', newSrc);

                                // Reset border dari semua thumbnail
                                document.querySelectorAll('.thumbnail-img').forEach(img => {
                                    img.classList.remove('active-thumb');
                                    img.style.border = '1px solid #dee2e6';
                                });

                                // Highlight thumbnail aktif
                                this.classList.add('active-thumb');
                                this.style.border = '2px solid #994D1C';
                            });
                        });
                    </script>
                @endpush
            </div>



            <!-- Form Input Produk -->
            <div class="col-lg-6">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $products->id }}">
                    <input type="hidden" name="name" value="{{ $products->name }}">
                    <input type="hidden" name="price" value="{{ $products->price }}">
                    <input type="hidden" name="image" value="{{ $products->image }}">
                    <!-- Nama & Rating -->
                    <div class="mb-5">
                        <h6 class="text-muted">Maju Mundur Keramik</h6>
                        <h3>{{ $products->name }}</h3>
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

                    <p class="fw-semibold">Variasi</p>
                    <div class="d-flex gap-3 mb-3">
                        <!-- Gambar utama -->
                        <label>
                            <input type="radio" name="variation" value="main-{{ $products->id }}" hidden checked>
                            <img src="{{ asset($products->image) }}"
                                class="img-thumbnail1 rounded-3 border variation-img active-variation"
                                style="width: 110px; border: 2px solid #994D1C; cursor: pointer;" alt="Variasi Utama">
                        </label>

                        <!-- Gambar dari relasi images -->
                        @foreach ($products->images as $image)
                            <label>
                                <input type="radio" name="variation" value="{{ $image->id }}" hidden>
                                <img src="{{ asset($image->image_path) }}"
                                    class="img-thumbnail1 rounded-3 border variation-img"
                                    style="width: 110px; cursor: pointer;" alt="Variasi Tambahan">
                            </label>
                        @endforeach


                    </div>
                    @push('scripts')
                        <script>
                            document.querySelectorAll('.variation-img').forEach(img => {
                                img.addEventListener('click', function() {
                                    // Hapus highlight dari semua variasi
                                    document.querySelectorAll('.variation-img').forEach(i => {
                                        i.classList.remove('active-variation');
                                        i.style.border = '1px solid #dee2e6';
                                    });

                                    // Tambahkan highlight ke thumbnail yang dipilih
                                    this.classList.add('active-variation');
                                    this.style.border = '2px solid #994D1C';

                                    // Pilih radio button terkait
                                    const radio = this.previousElementSibling;
                                    if (radio) {
                                        radio.checked = true;
                                    }
                                });
                            });
                        </script>
                    @endpush


                    <!-- Ukuran -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <p class="fw-semibold">Ukuran</p>
                            <a href="#" class="text-secondary text-decoration-none">
                                <img src="/img/massage.svg" class="img-fluid" alt="Ukuran"> Petunjuk Ukuran
                            </a>
                        </div>

                        <div class="btn-group gap-4" role="group">
                            <input type="radio" class="btn-check" name="size" value="Besar" id="sizeBesar"
                                autocomplete="off" checked>
                            <label class="btn bg-light rounded-pill px-4 py-2" for="sizeBesar">Besar</label>

                            <input type="radio" class="btn-check" name="size" value="Sedang" id="sizeSedang"
                                autocomplete="off">
                            <label class="btn bg-light rounded-pill px-4 py-2" for="sizeSedang">Sedang</label>

                            <input type="radio" class="btn-check" name="size" value="Kecil" id="sizeKecil"
                                autocomplete="off">
                            <label class="btn bg-light rounded-pill px-4 py-2" for="sizeKecil">Kecil</label>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label for="quantity" class="fw-semibold">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                            class="form-control rounded-pill px-3 py-2 w-50">
                    </div>

                    <!-- Tombol dan Info Ongkir -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center">
                            <button type="submit"
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

                        <div class="d-flex align-items-center gap-3 mt-3">
                            <img src="/img/truck-fast.svg" alt="Truck" class="img-fluid">
                            <p class="fw-medium m-0">Gratis ongkir untuk pembelian minimal Rp200.000</p>
                        </div>
                    </div>
                </form>
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
                <a href="{{ route('homepage') }}" class="fw-light" style="color: #994D1C;text-decoration: none">
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
