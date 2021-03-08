<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesWantedPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'game_id',
        'position_id',
        'amount'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function position()
    {
        return $this->belongsTo(Positions::class);
    }
}
