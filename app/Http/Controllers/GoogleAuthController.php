<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            $avatarPath = null;

            // Ambil dan simpan avatar ke public/avatars jika tersedia
            if ($googleUser->getAvatar()) {
                try {
                    $avatarContent = Http::get($googleUser->getAvatar())->body();
                    $filename = Str::uuid() . '.jpg';
                    $fullPath = public_path('avatars/' . $filename);

                    // Buat folder jika belum ada
                    if (!file_exists(public_path('avatars'))) {
                        mkdir(public_path('avatars'), 0755, true);
                    }

                    file_put_contents($fullPath, $avatarContent);
                    $avatarPath = 'avatars/' . $filename; // untuk asset()
                } catch (\Exception $e) {
                    // Gagal ambil avatar, biarkan kosong
                    $avatarPath = null;
                }
            }

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }

                // Hanya update avatar jika sebelumnya belum ada
                if (!$user->avatar && $avatarPath) {
                    $user->avatar = $avatarPath;
                }

                $user->save();
            } else {
                $user = User::create([
                    'name'       => $googleUser->getName(),
                    'email'      => $googleUser->getEmail(),
                    'google_id'  => $googleUser->getId(),
                    'password'   => bcrypt(Str::random(24)),
                    'avatar'     => $avatarPath,
                    'role'       => 'user',
                ]);
            }

            Auth::login($user);

            return $user->role === 'admin'
                ? redirect('/admin')->with('success', 'Berhasil login sebagai admin.')
                : redirect('/homepage')->with('success', 'Berhasil login dengan Google.');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Login dengan Google gagal.']);
        }
    }
}
