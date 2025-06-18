<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth/register');
    }

    public function store(Request $request)
    {

        try {
            // Validasi input
            $validated = $request->validate([
                'name'     => ['required', 'string'],
                'email'    => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            // Simpan user baru
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Login otomatis setelah berhasil daftar
            Auth::login($user);

            return redirect('/')->with('success', 'Akun berhasil dibuat!');
        } catch (ValidationException $e) {
            // Jika validasi gagal, Laravel otomatis redirect ke form dengan pesan error
            throw $e;
        } catch (Exception $e) {
            // Tangani error lainnya (misalnya error DB, atau kesalahan tak terduga)
            Log::error('Register error: ' . $e->getMessage());

            return back()->withInput()->withErrors([
                'register' => 'Terjadi kesalahan saat membuat akun. Silakan coba lagi nanti.',
            ]);
        }
    }
}
