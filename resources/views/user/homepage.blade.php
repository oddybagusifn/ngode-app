@extends('layouts.user')

@section('user-content')
    <div class="container-fluid py-5">
        <h4 class="text-body-secondary fw-semibold">PRODUK</h4>

        {{-- Container hasil pencarian (ditampilkan saat search aktif) --}}
        <div id="searchResult" class="row flex-row-reverse justify-content-end mb-4" style="display: none;">
            {{-- Diisi via JavaScript --}}
        </div>

        {{-- Container semua produk --}}
        <div id="productList" class="product row flex-row-reverse justify-content-end">
            @foreach ($products as $product)
                <div class="col-12 col-md-6 col-lg-3 g-4 d-flex">
                    <a href="{{ route('homepage.product', $product->id) }}" class="w-100 text-decoration-none">
                        <div class="card h-100 d-flex flex-column border-0">
                            <div style="height: 300px; overflow: hidden;">
                                <img src="{{ asset($product->image) }}" alt="Gambar Produk" class="img-fluid w-100 h-100 rounded-top-3"
                                    style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between rounded-3 rounded-top-0"
                                style="border: 1px solid #F5CCA0; border-top: none;">
                                <p class="card-text fw-semibold">{{ $product->name }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rating d-flex">
                                        @php $rating = round($product->rating); @endphp
                                        @for ($i = 0; $i < $rating; $i++)
                                            <img src="{{ asset('img/star.svg') }}" alt="star" width="20">
                                        @endfor
                                        @for ($i = $rating; $i < 5; $i++)
                                            <img src="{{ asset('img/star-disabled.svg') }}" alt="star-disabled"
                                                width="20">
                                        @endfor
                                    </div>
                                    <div class="review">
                                        <p class="text-secondary mt-1 mb-0">{{ $product->feedbacks->count() }} Ulasan</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                    <p class="fw-semibold fs-5 mb-0">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    <form action="">
                                        <button type="submit" class="btn border-0 p-0">
                                            <img src="{{ asset('img/addProduct.svg') }}" alt="add">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const productList = document.getElementById('productList');
            const originalContent = productList.innerHTML;
            let controller = null;

            // ==== SEARCH ====
            searchInput.addEventListener('input', function() {
                const keyword = this.value.trim();

                if (controller) controller.abort();
                controller = new AbortController();

                if (keyword.length < 1) {
                    productList.innerHTML = originalContent;
                    return;
                }

                fetch(`/search/products?keyword=${encodeURIComponent(keyword)}`, {
                        signal: controller.signal
                    })
                    .then(response => response.json())
                    .then(products => {
                        renderProducts(products);
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Fetch error:', error);
                        }
                    });
            });

            // ==== FILTER KATEGORI ====
            document.querySelectorAll('.dropdown-item[data-category]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const categoryId = this.dataset.category;
                    searchInput.value = '';
                    if (controller) controller.abort();

                    if (categoryId === 'all') {
                        productList.innerHTML = originalContent;
                        return;
                    }

                    fetch(`/filter/products?category=${categoryId}`)
                        .then(res => res.json())
                        .then(products => {
                            renderProducts(products);
                        })
                        .catch(err => console.error('Filter error:', err));
                });
            });

            // ==== FUNGSI RENDER PRODUK ====
            function renderProducts(products) {
                productList.innerHTML = '';

                if (products.length === 0) {
                    productList.innerHTML = '<p class="text-muted">Produk tidak ditemukan.</p>';
                    return;
                }

                products.forEach(product => {
                    const rating = Math.round(product.rating || 0);
                    const filledStars = '<img src="/img/star.svg" width="20">'.repeat(rating);
                    const emptyStars = '<img src="/img/star-disabled.svg" width="20">'.repeat(5 - rating);

                    const item = `
                <div class="col-12 col-md-6 col-lg-3 g-4 d-flex">
                    <a href="/homepage/product/${product.id}" class="w-100 text-decoration-none">
                        <div class="card h-100 d-flex flex-column border-0">
                            <div style="height: 300px; overflow: hidden;">
                                <img src="/${product.image}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between rounded-3 rounded-top-0" style="border: 1px solid #F5CCA0; border-top: none;">
                                <p class="card-text fw-semibold">${product.name}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rating d-flex">${filledStars}${emptyStars}</div>
                                    <div class="review">
                                        <p class="text-secondary mt-1 mb-0">${product.feedbacks.length} Ulasan</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                    <p class="fw-semibold fs-5 mb-0">Rp${Number(product.price).toLocaleString('id-ID')}</p>
                                    <form action="">
                                        <button type="submit" class="btn border-0 p-0">
                                            <img src="/img/addProduct.svg" alt="add">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>`;
                    productList.innerHTML += item;
                });
            }
        });
    </script>
@endpush
