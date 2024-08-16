<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

   // Relasi ke model Task
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
