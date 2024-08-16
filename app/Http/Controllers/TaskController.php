<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    // Mengambil semua data tugas
    public function index() 
    {
        $tasks = Task::all(); // Mengambil semua data tugas dari database
        return TaskResource::collection($tasks); // Mengembalikan semua data tugas dalam bentuk koleksi resource JSON
    }

    // Membuat tugas baru
    public function store(Request $request)
    {
        // Validasi data masukan
        $validated = $request->validate([
            'title' => 'required|max:255', 
            'description' => 'required', 
            'status' => 'required', 
            'project_id' => 'required|exists:projects,id', 
            'assigned_to' => 'required|exists:users,id', 
        ]);

        // Memastikan pengguna telah login sebelum melanjutkan
        if ($request->user()) {
            // Membuat dan menyimpan tugas baru ke dalam database
            $task = Task::create($validated);

            // Mengembalikan respons dengan data tugas yang baru dibuat
            return new TaskResource($task);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401); // Jika tidak terautentikasi, mengembalikan error 401
        }
    }

    // Memperbarui tugas yang sudah ada
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id); // Mencari tugas berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404

        // Validasi data masukan
        $validated = $request->validate([
            'title' => 'sometimes|required|max:255', // Judul opsional, tetapi jika ada harus maksimal 255 karakter
            'description' => 'sometimes|required', // Deskripsi opsional, tetapi jika ada wajib diisi
            'status' => 'sometimes|required', // Status opsional, tetapi jika ada wajib diisi
            'project_id' => 'sometimes|required|exists:projects,id', // Project ID opsional, tetapi jika ada harus valid dan ada di tabel projects
            'assigned_to' => 'sometimes|required|exists:users,id', // User ID opsional, tetapi jika ada harus valid dan ada di tabel users
        ]);

        // Mengupdate tugas dengan data yang sudah divalidasi
        $task->update($validated);

        // Mengembalikan tugas yang telah diperbarui dalam bentuk resource JSON
        return new TaskResource($task);
    }

    // Menghapus tugas
    public function destroy($id)
    {
        $task = Task::findOrFail($id); // Mencari tugas berdasarkan ID, jika tidak ditemukan akan mengembalikan error 404
        $task->delete(); // Menghapus tugas dari database

        return response()->json(['message' => 'Task berhasil dihapus'], 200); // Mengembalikan respons JSON dengan pesan sukses
    }

    // Mengambil tugas yang ditugaskan kepada pengguna yang sedang login
    public function myTasks(Request $request)
    {
        $userId = $request->user()->id; // Mendapatkan ID pengguna yang sedang login

        $tasks = Task::where('assigned_to', $userId)->get(); // Mengambil tugas yang penanggung jawabnya adalah user yang sedang login

        return TaskResource::collection($tasks); // Mengembalikan koleksi resource task
    }
}
