<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Mengambil semua data proyek
    public function index() 
    {
        $projects = Project::all(); // Mengambil semua data proyek dari database
        return ProjectResource::collection($projects); // Mengembalikan semua data proyek dalam bentuk koleksi resource JSON
    }

    // Membuat proyek baru
    public function store(Request $request) 
    {
        // Validasi data masukan
        $validated = $request->validate([
            'name' => 'required|max:255', // Nama proyek wajib diisi dan maksimal 255 karakter
            'description' => 'required', // Deskripsi proyek wajib diisi
        ]);

        // Membuat dan menyimpan proyek baru ke dalam database
        $project = Project::create($validated);

        return new ProjectResource($project); // Mengembalikan data proyek yang baru dibuat dalam bentuk resource JSON
    }

    // Memperbarui data proyek yang sudah ada
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id); // Mencari proyek berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404

        // Validasi data masukan
        $validated = $request->validate([
            'name' => 'required|max:255', // Nama proyek wajib diisi dan maksimal 255 karakter
            'description' => 'required', // Deskripsi proyek wajib diisi
        ]);

        // Mengupdate proyek dengan data yang sudah divalidasi
        $project->update($validated);

        return new ProjectResource($project); // Mengembalikan data proyek yang diperbarui dalam bentuk resource JSON
    }

    // Menghapus proyek
    public function destroy($id)
    {
        $project = Project::findOrFail($id); // Mencari proyek berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404
        $project->delete(); // Menghapus proyek dari database

        return response()->json(['message' => 'Project deleted successfully']); // Mengembalikan respons JSON dengan pesan sukses
    }
}
