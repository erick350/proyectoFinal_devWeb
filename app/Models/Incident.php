<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = ['player_id', 'description', 'card_type', 'incident_date', 'suspension_until'];

    protected function casts(): array
    {
        return ['incident_date' => 'date', 'suspension_until' => 'date'];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
