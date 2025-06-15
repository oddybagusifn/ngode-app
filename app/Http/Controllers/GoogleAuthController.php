<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'user',
                ]);
            }

            Auth::login($user);

            // ✅ Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Berhasil login sebagai admin.');
            }

            return redirect('/homepage')->with('success', 'Berhasil login dengan Google.');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Login dengan Google gagal.']);
        }
    }
}
