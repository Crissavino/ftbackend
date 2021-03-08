<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'isFullySet',
        'online',
        'genre',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function AuthAccessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Positions::class);
    }

    public function daysAvailables()
    {
        return $this->hasMany(DaysAvailables::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
