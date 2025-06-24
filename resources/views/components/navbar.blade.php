@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();

    if ($user) {
        $initial = strtoupper(substr($user->name, 0, 1));

        $gradients = [
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)',
            'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
            'linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%)',
            'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        ];
        $bgGradient = $gradients[crc32($user->id ?? rand()) % count($gradients)];
    }
@endphp

<nav class="navbar navbar-expand-lg bg-body-light d-flex flex-column">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('homepage') }}">
            <img class="img-fluid" src="/img/brand.svg" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ms-5" id="navbarNavDropdown">
            <div class="nav-link w-100 d-flex justify-content-center gap-3 align-items-center">
                <form action="{{ route('products.search') }}" method="GET"
                    class="data-search w-25 p-0 m-0 d-flex align-items-center rounded-pill px-2"
                    style="height: 40px;border: 1px solid #F5CCA0">
                    <img class="img-fluid me-2" style="width: 20px" src="/img/search-normal.svg" alt="Icon Pencarian">
                    <input type="text" id="searchInput" class="form-control border-0 p-0 text-secondary"
                        placeholder="Pencarian" style="box-shadow: none;">

                </form>


                @php
                    use App\Models\Category;
                    $categories = Category::all(); // Pastikan model Category sudah ada dan table categories tersedia
                @endphp

                <div class="data-filter dropdown">
                    <button class="btn p-0 border-0 bg-transparent " type="button" id="filterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="img-fluid me-2" style="width: 45px" src="/img/filter.svg" alt="Icon Filter">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="#" data-category="all">Semua Kategori</a></li>
                        @foreach ($categories as $category)
                            <li><a class="dropdown-item" href="#"
                                    data-category="{{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <div class="d-flex flex-column flex-sm-row align-items-center ms-auto gap-2">
                <div class="me-3">
                    <div class="position-relative">
                        <button onclick="toggleCartSidebar()" class="btn border-0 p-0">
                            <img src="/img/cart.svg" width="45px" alt="Cart">
                            @if (isset($cartItemsCount) && $cartItemsCount > 0)
                                <span class="position-absolute start-100 badge rounded-pill bg-danger"
                                    style="top: 6px; transform: translate(-90%, 0); font-size: 0.6rem; min-width: 16px; height: 16px; padding: 2px 4px;">
                                    {{ $cartItemsCount }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>


                <a class="nav-link text-secondary rounded-circle p-0 me-3" href="#"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('img/favorite.svg') }}" width="45px" alt="Fav">
                </a>

                @if ($user)
                    <div class="dropdown">
                        <button class="d-flex align-items-center justify-content-center p-0 border-0 bg-transparent"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 40px; height: 40px; border-radius: 50%;">
                            @if ($user->google_id && $user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Profile"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
                            @else
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: {{ $bgGradient }};
                                    color: white; display: flex; align-items: center; justify-content: center;
                                    font-weight: bold;">
                                    {{ $initial }}
                                </div>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li><a class="dropdown-item" href="{{route('profile.page')}}">Profile</a></li>
                            @if ($user->role === 'admin')
                                <li><a class="dropdown-item" href="/admin">Dashboard</a></li>
                            @endif
                            <li>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="navbar-link container-fluid mt-3 p-4" style="border-bottom: 1px solid #F5CCA0">
        <ul class="navbar-nav gap-4">
            <li class="nav-item">
                @if ($user && $user->role === 'admin')
                    <a class="nav-link text-secondary active" aria-current="page" href="/admin">Terkait</a>
                @else
                    <a class="nav-link text-secondary active" aria-current="page" href="#">Terkait</a>
                @endif
            </li>
            <li class="nav-item"><a class="nav-link text-secondary" href="#">Terbaru</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="#">Terlaris</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="#">Teratas</a></li>
        </ul>
    </div>
</nav>

<style>
    .dropdown-menu {
        border: 2px solid #F5CCA0;
        border-radius: 8px;
        padding: 0.3rem 0;
        min-width: 300px;
        background-color: #fff;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
    }

    .dropdown-item {
        padding: 10px 16px;
        font-size: 14px;
        color: #333;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #9898981b;
        color: #000;
    }
</style>
