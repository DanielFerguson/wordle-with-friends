<?php

namespace App\Jobs;

use App\Models\Solution;
use App\Models\Word;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SelectSolutionWord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Check that a word has not already been selected for today
        $today = date('Y-m-d');

        if (Solution::where('date', $today)->exists()) {
            report("A solution for today already exists");
            return;
        }

        $num_words = Word::count();

        $random_id = rand(0, $num_words);

        $selected_word = Word::find($random_id);

        Solution::create([
            'date' => $today,
            'word_id' => $selected_word->id
        ]);
    }
}
