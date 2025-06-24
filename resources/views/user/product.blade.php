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
                                const mainImage = document.getElementById('mainImage');

                                // Tambahkan class fade-out
                                mainImage.classList.add('fade-out');

                                // Tunggu sampai animasi selesai (300ms), lalu ganti gambar
                                setTimeout(() => {
                                    mainImage.setAttribute('src', newSrc);
                                    mainImage.classList.remove('fade-out');
                                    mainImage.classList.add('fade-in');

                                    // Setelah selesai animasi masuk, hapus class fade-in (opsional)
                                    setTimeout(() => {
                                        mainImage.classList.remove('fade-in');
                                    }, 300);
                                }, 300);

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
                        <div class="card-body d-flex flex-column justify-content-between rounded-3 rounded-top-0"
                            style="border-top: none;">
                            <p class="card-text fw-semibold">{{ $products->name }}</p>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rating d-flex">
                                    @php
                                        $rating = round($products->rating); // Bulatkan rating ke angka bulat (1–5)
                                    @endphp

                                    {{-- Bintang Penuh --}}
                                    @for ($i = 0; $i < $rating; $i++)
                                        <img class="img-fluid" src="{{ asset('img/star.svg') }}" alt="star"
                                            width="20">
                                    @endfor

                                    {{-- Bintang Kosong --}}
                                    @for ($i = $rating; $i < 5; $i++)
                                        <img class="img-fluid" src="{{ asset('img/star-disabled.svg') }}"
                                            alt="star-disabled" width="20">
                                    @endfor
                                </div>

                                <div class="review">
                                    <p class="text-secondary mt-1 mb-0">{{ $products->feedbacks->count() }} Ulasan</p>
                                </div>
                            </div>
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
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn rounded-circle " id="btn-minus">
                                <img src="/img/min.svg" width="40" class="img-fluid" alt="">
                            </button>

                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="form-control rounded-pill px-3 py-2 w-25">

                            <button type="button" class="btn rounded-circle" id="btn-plus">
                                <img src="/img/plus.svg" width="40" class="img-fluid" alt="">
                            </button>
                        </div>
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
            <!-- Tombol Tab -->
            <div class="d-flex gap-3 my-5">
                <button class="fw-medium btn-tab border-0 bg-transparent active" data-tab="detail"
                    style="text-decoration: none">Detail</button>
                <button class="fw-medium btn-tab border-0 bg-transparent" data-tab="ulasan"
                    style="text-decoration: none">Ulasan</button>
            </div>


            <!-- Konten -->
            <div class="row g-5">
                <!-- Kiri: Tab Konten -->
                <div class="col-md-8">
                    <!-- Tab Detail -->
                    <div id="tab-detail" class="tab-content">
                        <h5 class=" mb-3 ">Detail</h5>
                        <p>{{ $products->description }}</p>
                    </div>

                    <!-- Tab Ulasan -->
                    <div id="tab-ulasan" class="tab-content d-none ">
                        <h5 class="mb-3 ">Ulasan Pelanggan</h5>

                        @foreach ($products->feedbacks as $feedback)
                            <div class="d-flex border-bottom py-4 gap-3" id="feedback-{{ $feedback->id }}">
                                <!-- Foto Profil -->
                                <div class="me-3">
                                    @php
                                        $feedbackUser = $feedback->user;
                                        $feedbackInitial = strtoupper(substr($feedbackUser->name, 0, 1));
                                        $gradients = [
                                            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                            'linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)',
                                            'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
                                            'linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%)',
                                            'linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%)',
                                            'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
                                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                        ];
                                        $bgGradient = $gradients[crc32($feedbackUser->id) % count($gradients)];
                                    @endphp

                                    @if ($feedbackUser->avatar && file_exists(public_path($feedbackUser->avatar)))
                                        <img src="{{ asset($feedbackUser->avatar) }}" alt="user" width="48"
                                            height="48" class="rounded-circle object-fit-cover">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                            style="width: 48px; height: 48px; background: {{ $bgGradient }};">
                                            {{ $feedbackInitial }}
                                        </div>
                                    @endif

                                </div>

                                <!-- Isi Ulasan -->
                                <div class="flex-grow-1">
                                    <!-- Nama dan Waktu -->
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <strong>{{ $feedback->user->name }}</strong>
                                        <span class="text-muted small">•
                                            {{ $feedback->created_at->diffForHumans() }}</span>
                                    </div>

                                    <!-- Rating -->
                                    <div class="d-flex gap-1 mb-2">
                                        @for ($i = 0; $i < $feedback->rating; $i++)
                                            <img src="{{ asset('img/star.svg') }}" alt="star" width="18">
                                        @endfor
                                        @for ($i = $feedback->rating; $i < 5; $i++)
                                            <img src="{{ asset('img/star-disabled.svg') }}" alt="star-disabled"
                                                width="18">
                                        @endfor
                                    </div>

                                    <!-- Review -->
                                    <p class="mb-2" style="line-height: 1.6;">{{ $feedback->review }}</p>

                                    <!-- Action -->
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <!-- Like -->

                                        <button class="btn-like btn border-0" data-id="{{ $feedback->id }}"
                                            data-liked="{{ $feedback->likes->contains('user_id', auth()->id()) ? '1' : '0' }}"
                                            style="color: {{ $feedback->likes->contains('user_id', auth()->id()) ? '#994D1C' : 'inherit' }}">
                                            <i class="fa-regular fa-thumbs-up"></i>
                                            <span class="like-count">{{ $feedback->likes->count() }}</span>
                                        </button>


                                        <!-- Comment Toggle -->
                                        <button type="button"
                                            class="btn btn-sm p-0 border-0 bg-transparent d-flex align-items-center gap-1 toggle-comment"
                                            data-id="{{ $feedback->id }}">
                                            <i class="fa-regular fa-comment"></i>
                                            <span>{{ $feedback->comments->count() }}</span>
                                        </button>
                                    </div>

                                    <!-- Form Komentar -->
                                    <div id="comment-form-{{ $feedback->id }}" class="mt-3 d-none">
                                        <form class="form-comment" data-id="{{ $feedback->id }}">
                                            @csrf
                                            <div class="d-flex gap-2 align-items-center border-bottom py-1">
                                                <input type="text" name="comment"
                                                    class="form-control border-0 shadow-none px-0"
                                                    style="background: transparent;" placeholder="Tulis komentar...">
                                                <button class="btn btn-sm px-3 text-white rounded-pill"
                                                    style="background-color: #994D1C;" type="submit">Kirim</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Daftar Komentar -->
                                    <div class="comments mt-2" id="comment-list-{{ $feedback->id }}">
                                        @foreach ($feedback->comments as $comment)
                                            @php
                                                $commentUser = $comment->user;
                                                $initial = strtoupper(substr($commentUser->name, 0, 1));
                                                $gradients = [
                                                    'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                                    'linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)',
                                                    'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
                                                    'linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%)',
                                                    'linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%)',
                                                    'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
                                                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                                ];
                                                $bgGradient = $gradients[crc32($commentUser->id) % count($gradients)];
                                            @endphp

                                            <div class="d-flex align-items-start gap-2 text-muted small mt-2">
                                                @if ($commentUser->avatar && file_exists(public_path($commentUser->avatar)))
                                                    <img src="{{ asset($commentUser->avatar) }}" alt="user"
                                                        width="32" height="32"
                                                        class="rounded-circle object-fit-cover">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                        style="width: 32px; height: 32px; background: {{ $bgGradient }};">
                                                        {{ $initial }}
                                                    </div>
                                                @endif

                                                <div>
                                                    <strong>{{ $commentUser->name }}</strong>: {{ $comment->comment }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        @endforeach


                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const csrfToken = '{{ csrf_token() }}';

                                document.querySelectorAll('.btn-like').forEach(button => {
                                    button.addEventListener('click', function() {
                                        const feedbackId = this.dataset.id;
                                        const isLiked = this.dataset.liked === '1';
                                        const spanCount = this.querySelector('.like-count');

                                        fetch(`/feedback/${feedbackId}/toggle-like`, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': csrfToken,
                                                    'Accept': 'application/json'
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                // Update UI sesuai respons backend
                                                this.dataset.liked = data.liked ? '1' : '0';
                                                spanCount.textContent = data.count;
                                                this.style.color = data.liked ? '#994D1C' : 'inherit';
                                            })
                                            .catch(error => {
                                                console.error('Toggle Like error:', error);
                                            });
                                    });
                                });
                            });



                            document.addEventListener('DOMContentLoaded', function() {
                                const input = document.getElementById('quantity');
                                const btnMinus = document.getElementById('btn-minus');
                                const btnPlus = document.getElementById('btn-plus');

                                btnMinus.addEventListener('click', function() {
                                    let current = parseInt(input.value);
                                    if (current > parseInt(input.min)) {
                                        input.value = current - 1;
                                    }
                                });

                                btnPlus.addEventListener('click', function() {
                                    let current = parseInt(input.value);
                                    input.value = current + 1;
                                });
                            });
                        </script>

                        <script>
                            $('.form-comment').on('submit', function(e) {
                                e.preventDefault();

                                const form = $(this);
                                const feedbackId = form.data('id');
                                const commentInput = form.find('input[name="comment"]');
                                const commentText = commentInput.val();

                                $.ajax({
                                    url: `/feedback/${feedbackId}/comment`,
                                    method: 'POST',
                                    data: form.serialize(),
                                    success: function(res) {
                                        const user = res.user;
                                        const comment = res.comment;

                                        const initial = user.name.charAt(0).toUpperCase();
                                        const gradients = [
                                            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                            'linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)',
                                            'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
                                            'linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%)',
                                            'linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%)',
                                            'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
                                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                        ];
                                        const gradient = gradients[user.id % gradients.length];
                                        const baseAsset = "{{ asset('') }}";

                                        const avatarHTML = user.avatar ?
                                            `<img src="${user.avatar.startsWith('http') ? user.avatar : baseAsset + user.avatar}" alt="user" width="32" height="32" class="rounded-circle object-fit-cover">` :
                                            `<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                            style="width: 32px; height: 32px; background: ${gradient};">
                            ${initial}
                        </div>`;

                                        const newCommentHTML = `
                    <div class="d-flex align-items-start gap-2 text-muted small mt-2">
                        ${avatarHTML}
                        <div>
                            <strong>${user.name}</strong>: ${comment}
                        </div>
                    </div>`;

                                        $(`#comment-list-${feedbackId}`).append(newCommentHTML);
                                        commentInput.val('');
                                    },
                                    error: function() {
                                        alert('Gagal mengirim komentar.');
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>

                <!-- Kanan: Banner & Grafik Rating -->
                <div class="col-md-4 d-flex flex-column gap-5">
                    <!-- Grafik Rating (dibungkus dengan container) -->
                    <div id="rating-graph-container" class="d-none"> {{-- Hidden by default --}}
                        <h6>Rata-rata Rating: {{ number_format($products->rating, 1) }}/5</h6>

                        @php
                            $ratingCounts = $products->feedbacks->groupBy('rating')->map->count();
                            $totalFeedback = $products->feedbacks->count();
                        @endphp

                        @for ($i = 5; $i >= 1; $i--)
                            @php
                                $count = $ratingCounts[$i] ?? 0;
                                $percent = $totalFeedback > 0 ? ($count / $totalFeedback) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span style="width: 15px;">{{ $i }}</span>
                                <div class="progress flex-fill" style="height: 8px;">
                                    <div class="progress-bar bg-warning progress-bar-animated-rating"
                                        style="width: 0%; transition: width 1s ease;"
                                        data-target-width="{{ $percent }}">
                                    </div>
                                </div>
                                <span class="text-muted small">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                    <!-- Banner -->
                    <img src="/img/banner.svg" width="400" class="img-fluid" alt="Banner">
                </div>



            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.btn-tab');
            const contents = document.querySelectorAll('.tab-content');
            const ratingContainer = document.getElementById('rating-graph-container');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = this.dataset.tab;

                    // Toggle aktif tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Tampilkan konten tab
                    contents.forEach(content => content.classList.add('d-none'));
                    document.getElementById('tab-' + target).classList.remove('d-none');

                    if (target === 'ulasan') {
                        // Tampilkan grafik rating
                        ratingContainer.classList.remove('d-none');

                        // Reset dan animate ulang progress bar
                        document.querySelectorAll('.progress-bar-animated-rating').forEach(bar => {
                            bar.style.transition = 'none';
                            bar.style.width = '0%';
                            void bar.offsetWidth; // Trigger reflow
                            bar.style.transition = 'width 1s ease';

                            const targetWidth = bar.getAttribute('data-target-width');
                            setTimeout(() => {
                                bar.style.width = targetWidth + '%';
                            }, 50);
                        });

                    } else {
                        // Sembunyikan grafik rating saat bukan tab ulasan
                        ratingContainer.classList.add('d-none');
                    }
                });
            });
        });
    </script>











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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle form komentar
            document.querySelectorAll('.toggle-comment').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const form = document.getElementById('comment-form-' + id);
                    form.classList.toggle('d-none');
                });
            });

            // AJAX submit komentar
            document.querySelectorAll('.form-comment').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    const input = this.querySelector('input[name="comment"]');
                    const comment = input.value.trim();
                    const token = this.querySelector('input[name="_token"]').value;

                    if (comment === '') return;

                    fetch(`/feedback/${id}/comment`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                comment
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Tambahkan komentar ke daftar
                            const list = document.getElementById('comment-list-' + id);
                            const div = document.createElement('div');
                            div.classList.add('text-muted', 'small', 'mt-1');
                            div.innerHTML = `<strong>${data.user}</strong>: ${data.comment}`;
                            list.appendChild(div);

                            // Reset & hide field
                            input.value = '';
                            document.getElementById('comment-form-' + id).classList.add(
                                'd-none');
                        });
                });
            });
        });
    </script>
    </div>
@endsection
