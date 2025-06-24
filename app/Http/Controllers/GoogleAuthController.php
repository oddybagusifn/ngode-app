<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            $avatarPath = null;
            if ($googleUser->getAvatar()) {
                try {
                    $avatarContent = Http::get($googleUser->getAvatar())->body();
                    $filename = Str::uuid() . '.jpg';
                    $fullPath = public_path('avatars/' . $filename);

                    if (!file_exists(public_path('avatars'))) {
                        mkdir(public_path('avatars'), 0755, true);
                    }

                    file_put_contents($fullPath, $avatarContent);
                    $avatarPath = 'avatars/' . $filename;
                } catch (\Exception $e) {
                    $avatarPath = null;
                }
            }

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }

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

            Auth::login($user, true);
            $request->session()->regenerate();

            return $user->role === 'admin'
                ? redirect('/admin')
                : redirect('/homepage');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Login dengan Google gagal.']);
        }
    }
}
