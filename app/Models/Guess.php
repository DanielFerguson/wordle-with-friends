<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitive_game_id',
        'attempt'
    ];

    protected $hidden = [
        'id',
        'competitive_game_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'attempt' => 'json',
    ];
}
