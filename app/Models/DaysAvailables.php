<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaysAvailables extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'dayOfTheWeek',
        'available',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
