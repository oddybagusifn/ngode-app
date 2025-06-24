@extends('layouts.user')

@section('user-content')
    <div class="container py-5">
        <h4 class="mb-4">Umpan Balik</h4>

        <!-- Form -->
        <form action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @foreach ($purchasedProducts as $product)
                <div class="mb-5 border rounded p-4 bg-white">
                    <div class="mb-3">
                        <h5 class="fw-bold">{{ $product->name }}</h5>
                        <p class="text-muted">Silakan berikan penilaian dan ulasan untuk produk ini.</p>
                    </div>

                    <!-- Penilaian -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">Penilaian</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Sangat Buruk</span>
                                <div class="d-flex gap-2 rating-block" data-product-id="{{ $product->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <img src="{{ asset('img/star-disabled.svg') }}" alt="star" width="32"
                                            height="32" class="star" data-star-value="{{ $i }}">
                                    @endfor
                                </div>
                                <span class="text-muted">Sangat Baik</span>
                            </div>
                            <input type="hidden" name="ratings[{{ $product->id }}]" id="rating-{{ $product->id }}"
                                value="0">
                        </div>


                        <!-- Ulasan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">Ulasan</label>
                            <textarea class="form-control" name="reviews[{{ $product->id }}]" rows="3" placeholder="Tuliskan ulasan Anda"></textarea>
                        </div>

                        <!-- Upload media -->
                        <div class="mb-4 d-flex flex-column flex-md-row gap-3">
                            <!-- Ambil Gambar -->
                            <div class="flex-fill text-center border rounded py-4 px-2 position-relative">
                                <label for="photo-{{ $product->id }}" class="d-block" style="cursor: pointer;">
                                    <i class="fa fa-camera fa-2x mb-2" style="color: #994D1C;"></i>
                                    <p class="mb-0 small">*Silakan ambil gambar menggunakan kamera Anda</p>
                                </label>
                                <input type="file" accept="image/*" capture="environment"
                                    name="photos[{{ $product->id }}]" id="photo-{{ $product->id }}" class="d-none">
                            </div>

                            <!-- Ambil Video -->
                            <div class="flex-fill text-center border rounded py-4 px-2 position-relative">
                                <label for="video-{{ $product->id }}" class="d-block" style="cursor: pointer;">
                                    <i class="fa fa-video fa-2x mb-2" style="color: #994D1C;"></i>
                                    <p class="mb-0 small">*Silakan ambil video menggunakan kamera Anda</p>
                                </label>
                                <input type="file" accept="video/*" capture="environment"
                                    name="videos[{{ $product->id }}]" id="video-{{ $product->id }}" class="d-none">
                            </div>

                            <!-- Upload dari Perangkat -->
                            <div class="flex-fill text-center border rounded py-4 px-2 position-relative">
                                <label for="file-{{ $product->id }}" class="d-block" style="cursor: pointer;">
                                    <i class="fa fa-upload fa-2x mb-2" style="color: #994D1C;"></i>
                                    <p class="mb-0 small">*Silakan unggah file dari perangkat Anda</p>
                                </label>
                                <input type="file" name="uploads[{{ $product->id }}]" id="file-{{ $product->id }}"
                                    class="d-none">
                            </div>
                        </div>
                </div>
            @endforeach

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('homepage') }}" class="btn btn-outline-secondary w-25">Lewati</a>
                <button type="submit" class="btn text-white w-25" style="background-color: #994D1C;">Kirimkan</button>
            </div>
        </form>
    </div>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Script rating pakai gambar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeSrc = "{{ asset('img/star.svg') }}";
            const inactiveSrc = "{{ asset('img/star-disabled.svg') }}";

            const ratingBlocks = document.querySelectorAll('.rating-block');

            ratingBlocks.forEach(block => {
                const productId = block.dataset.productId;
                const stars = block.querySelectorAll('.star');
                const input = document.getElementById(`rating-${productId}`);

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const selectedValue = parseInt(star.dataset.starValue);

                        input.value = selectedValue;

                        stars.forEach(s => {
                            const val = parseInt(s.dataset.starValue);
                            s.src = val <= selectedValue ? activeSrc : inactiveSrc;
                        });
                    });
                });
            });
        });
    </script>
@endsection
