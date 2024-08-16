<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthenticationController extends Controller
{
    // Metode untuk login dan mendapatkan token autentikasi
    public function login (Request $request)
    {
        // Validasi data masukan
        $request->validate([
            'email' => 'required|email', // Email wajib diisi dan harus valid
            'password' => 'required' // Password wajib diisi
        ]);

        // Mencari pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika pengguna tidak ditemukan atau password tidak cocok
        if (! $user || ! Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'], // Mengembalikan error jika kredensial salah
            ]);
        }

        // Mengembalikan token autentikasi jika login berhasil
        return $user->createToken('user login')->plainTextToken;
    }

    // Metode untuk logout dan menghapus token autentikasi
    public function logout (Request $request)
    {
        // Menghapus token akses yang sedang digunakan oleh pengguna
        $request->user()->currentAccessToken()->delete();
    }

    // Mengambil informasi pengguna yang sedang login
    public function me (Request $request)
    {
        return response()->json(Auth::user()); // Mengembalikan data pengguna yang sedang login dalam bentuk JSON
    }
}
