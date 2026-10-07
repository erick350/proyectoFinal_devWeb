<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matchday extends Model
{
    protected $fillable = ['number', 'game_date', 'description'];

    protected function casts(): array
    {
        return ['game_date' => 'date'];
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }
}
