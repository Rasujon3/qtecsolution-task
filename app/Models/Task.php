<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $fillable = [
        'title', 'description', 'status', 'priority', 'due_date'
    ];
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
        };
    }
}
