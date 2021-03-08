<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'game_id',
        'country',
        'countryCode',
        'province',
        'provinceCode',
        'city',
        'lat',
        'lng',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
