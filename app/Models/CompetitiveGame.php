<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitiveGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'attempts',
        'completed'
    ];

    public function guess(String $guess)
    {
        // Check that there are less than 5 attempts
        if ($this->attempts < 5) {
            // TODO: 
        }

        if ($this->completed) {
            // TODO: Return that the game has been completed
        }

        // Attempt to guess the solution
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
