<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'gender',
        'games_type_id',
        'games_wanted_position_id',
        'games_location_id',
        'cost',
        'when_play'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wantedPosition()
    {
        return $this->hasMany(GamesWantedPosition::class);
    }
}
