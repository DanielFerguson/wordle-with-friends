<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WordListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Word::truncate();

        $csv_file = fopen(base_path("database/data/words.csv"), 'r');

        while (($data = fgetcsv($csv_file, 5, ',')) !== false) {
            if ($data[0] == "") continue;

            Word::create(['word' => $data[0]]);
        }

        fclose($csv_file);
    }
}
