<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompetitiveGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'attempts',
        'completed'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function guess(String $guess)
    {
        if ($this->completed) {
            return;
        }

        if ($this->attempts >= 5) {
            return;
        }

        $guess_array = str_split($guess);
        $solution_array = str_split($this->solution());

        $result = [];
        $correct_letters = 0;

        foreach ($guess_array as $index => $guess_char) {
            if ($guess_char == $solution_array[$index]) {
                $correct_letters += 1;
                array_push($result, ['char' => $guess_char, 'color' => 'green']);
            } else if (in_array($guess_char, $solution_array)) {
                array_push($result, ['char' => $guess_char, 'color' => 'yellow']);
            } else {
                array_push($result, ['char' => $guess_char, 'color' => 'gray']);
            }
        }

        Guess::create([
            'competitive_game_id' => $this->id,
            'attempt' => $result
        ]);

        if ($correct_letters == 5) {
            $this->completed = true;
        }

        $this->increment('attempts');
        $this->save();
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }

    private function solution()
    {
        return Solution::where('date', $this->date)->firstOrFail()->word->word;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
