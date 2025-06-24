@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
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
@endphp


<nav class="navbar navbar-expand-lg bg-body-transparent">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('homepage') }}">
            <img class="img-fluid" src="/img/brand.svg" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ms-5" id="navbarNavDropdown">
            <div class="nav-link w-100">
                <ul class="navbar-nav gap-4">
                    <li class="nav-item">
                        <a class="nav-link text-secondary active" aria-current="page"
                            href="/admin">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#">Analisis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#">Sri si AI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#">Data</a>
                    </li>
                </ul>
            </div>
            {{-- Container tombol notifikasi dan avatar --}}
            <div class="d-flex flex-column flex-sm-row align-items-center ms-auto gap-2">
                {{-- Tombol Notifikasi --}}
                <a class="nav-link text-secondary rounded-circle p-0 me-3" href="#"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('img/notifIcon.svg') }}" alt="Notifikasi">
                </a>

                {{-- Dropdown Avatar --}}
                <div class="dropdown">
                    <button class="d-flex align-items-center justify-content-center p-0 border-0 bg-transparent"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 40px; height: 40px; border-radius: 50%;">
                        @if ($user->google_id && $user->avatar)
                            <img src="{{ $user->avatar }}" alt="Profile"
                                style="width: 40px; height: 40px; border-radius: 50%;">
                        @else
                            <div
                                style="
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: {{ $bgGradient }};
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                    ">
                                {{ $initial }}
                            </div>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="{{route('profile.page')}}">Profile</a></li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>


        </div>
    </div>
</nav>
