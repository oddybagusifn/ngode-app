<div class="d-flex flex-column" style="height: 100vh;">
    <div class="header py-3 d-flex align-items-center gap-2" style="border-bottom: 2px solid #F5CCA0">
        <img src="/img/cart.svg" width="50" alt="Cart Icon">
        <h5 class="fw-semibold m-0">Keranjang</h5>
        <button onclick="toggleCartSidebar()" class="btn ms-auto p-0 border-0 bg-transparent">
            <i class="fa fa-times fa-lg"></i>
        </button>
    </div>

    <div class="flex-grow-1 overflow-auto py-3 px-2">
        @if ($cartItems->isEmpty())
            <div class="d-flex justify-content-center align-items-center h-100" style="">
                <p class="text-muted">Belum ada produk yang ada di keranjang</p>
            </div>
        @else
            @foreach ($cartItems as $item)
                <a href="{{ route('homepage.product', $item->product_id) }}" style="text-decoration: none">
                    <div class="card mb-3">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="rounded img-fluid"
                                style="width: 100px; height: 100px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted d-block">Price: IDR
                                    {{ number_format($item->price, 0, ',', '.') }}</small>
                                @if ($item->size)
                                    <small class="text-muted d-block">Size: {{ $item->size }}</small>
                                @endif
                                <small class="text-muted d-block">Quantity: {{ $item->quantity }}</small>
                                <div class="mt-1">
                                    <strong>Subtotal: IDR
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <form action="{{ route('cart.delete', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm text-dark p-1">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

    <div class="footer d-flex sticky-bottom" style="border-top: 2px solid #F5CCA0;margin-top: auto;">
        <div class="d-flex align-items-center justify-content-between  py-3 w-100">
            <div class="d-flex flex-column justify-content-center">
                <p class="m-0">Total</p>
                <p class="fw-semibold m-0 fs-5">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
            <a href="{{route('checkout.index')}}" class="text-decoration-none fw-semibold text-light py-2 px-3 rounded-pill" style="background-color: #994D1C">
                Checkout ({{ $cartItems->count() }})
            </a>
        </div>
    </div>

</div>
