<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    protected $fillable = ['player_id', 'matchday_id', 'quantity'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function matchday(): BelongsTo
    {
        return $this->belongsTo(Matchday::class);
    }
}
