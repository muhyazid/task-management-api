<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'status', 'project_id', 'assigned_to'
    ];

    // Relasi ke model Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relasi ke model User sebagai penanggung jawab
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

}
