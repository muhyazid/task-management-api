<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul tugas
            $table->text('description')->nullable(); // Deskripsi tugas, bisa null
            $table->string('status')->default('pending'); // Status tugas, default pending
            $table->timestamps();

            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Foreign key ke tabel projects
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade'); // Foreign key ke tabel users
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
