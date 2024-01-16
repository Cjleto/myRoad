<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $initial_moods = config('myconstants.initial_moods');

        foreach ($initial_moods as $mood) {
            \App\Models\Mood::create([
                'name' => $mood,
            ]);
        }
    }
}
