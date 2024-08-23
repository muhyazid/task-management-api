<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'avatar' => 'sometimes|file|image|max:5000', 
        ]);

        // Enkripsi password sebelum disimpan ke database
        $validated['password'] = Hash::make($validated['password']);

        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            // Simpan file avatar ke direktori 'avatars' di dalam storage
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path; // Simpan path avatar ke dalam database
        }

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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Jika password diupdate, enkripsi password sebelum disimpan
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
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
