<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(HabitSession::class);
    }

    public function activeSessions()
    {
        return $this->sessions()->whereNull('end_time');
    }
}
