@extends('layouts.user')

@section('title', 'Profil Saya')

@section('user-content')
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
    $bgGradient = $gradients[crc32($user->id) % count($gradients)];
@endphp

<div class="container py-5 px-0" style="max-width: 1000px;">
    <h2 class="fw-bold mb-4" style="color: #333;">Profil Saya</h2>

    <div class="d-flex flex-column flex-md-row justify-content-center align-items-start gap-4">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            @if ($user->avatar && file_exists(public_path($user->avatar)))
                <img src="{{ asset($user->avatar) }}" alt="Avatar"
                    width="200" height="200" class="rounded-circle object-fit-cover" />
            @else
                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center"
                    style="width: 140px; height: 140px; background: {{ $bgGradient }}; font-size: 48px;">
                    {{ $initial }}
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="w-100">
            <div class="mb-4">
                <label class="form-label text-muted">Nama Lengkap</label>
                <div class="profile-field">{{ $user->name }}</div>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted">Email</label>
                <div class="profile-field">{{ $user->email }}</div>
            </div>
            <div>
                <label class="form-label text-muted">Peran</label>
                <div class="profile-field text-capitalize">{{ $user->role }}</div>
            </div>

            <a href="#" class="btn text-white mt-4"
                style="background-color: #994D1C; padding: 6px 24px;">Edit Profil</a>
        </div>
    </div>
</div>

<style>
    .profile-field {
        padding: 6px 0;
        font-size: 16px;
        color: #333;
        border-bottom: 1.5px solid #ddd;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
    }
</style>
@endsection
