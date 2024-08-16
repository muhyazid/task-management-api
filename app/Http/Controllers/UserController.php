<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Mengambil semua data pengguna
    public function index()
    {
        $users = User::all(); // Mengambil semua data pengguna dari database
        return UserResource::collection($users); // Mengembalikan semua data pengguna dalam bentuk koleksi resource JSON
    }

    // Mengambil data pengguna yang sedang login
    public function me(Request $request)
    {
        return new UserResource($request->user()); // Mengembalikan data pengguna yang sedang login dalam bentuk resource JSON
    }

    // Membuat pengguna baru
    public function store(Request $request)
    {
        // Validasi data masukan
        $validated = $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
        ]);

        // Enkripsi password sebelum disimpan ke database
        $validated['password'] = Hash::make($validated['password']);

        // Membuat dan menyimpan pengguna baru ke dalam database
        $user = User::create($validated);

        return new UserResource($user); // Mengembalikan data pengguna baru dalam bentuk resource JSON
    }

    // Memperbarui data pengguna yang ada
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Mencari pengguna berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404

        // Validasi data masukan
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255', 
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'sometimes|required|string|min:8', 
        ]);

        // Jika password diupdate, enkripsi password sebelum disimpan
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Mengupdate pengguna dengan data yang sudah divalidasi
        $user->update($validated);

        return new UserResource($user); // Mengembalikan data pengguna yang diperbarui dalam bentuk resource JSON
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Mencari pengguna berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404
        $user->delete(); // Menghapus pengguna dari database

        return response()->json(['message' => 'User berhasil dihapus'], 200); // Mengembalikan respon JSON dengan pesan sukses
    }
}
