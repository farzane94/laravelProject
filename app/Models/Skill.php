<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
