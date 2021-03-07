<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'country_id','deleted_at'
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withPivot('level');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
