<?php

namespace Database\Seeders;

use App\Enums\TravelVisibilityEnum;
use App\Models\Mood;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $travels = [
            // Travel 1
            [
                'id' => 'd408be33-aa6a-4c73-a2c8-58a70ab2ba4d',
                'slug' => 'jordan-360',
                'name' => 'Jordan 360°',
                'description' => 'Jordan 360°: the perfect tour to discover the suggestive Wadi Rum desert, the ancient beauty of Petra, and much more...',
                'numberOfDays' => 8,
            ],

            // Travel 2
            [
                'id' => '4f4bd032-e7d4-402a-bdf6-aaf6be240d53',
                'slug' => 'iceland-hunting-northern-lights',
                'name' => 'Iceland: hunting for the Northern Lights',
                'description' => 'Why visit Iceland in winter? Because it is between October and March that this land offers the spectacle of the Northern Lights...',
                'numberOfDays' => 8,
            ],

            // Travel 3
            [
                'id' => 'cbf304ae-a335-43fa-9e56-811612dcb601',
                'slug' => 'united-arab-emirates',
                'name' => 'United Arab Emirates: from Dubai to Abu Dhabi',
                'description' => 'At Dubai and Abu Dhabi everything is huge and majestic: here futuristic engineering works and modern infrastructures meet historical districts...',
                'numberOfDays' => 7,
            ],
        ];




        foreach ($travels as $travelData) {
            $travel = Travel::firstOrCreate(
                ['id' => $travelData['id']],
                [
                    'slug' => $travelData['slug'],
                    'name' => $travelData['name'],
                    'description' => $travelData['description'],
                    'numberOfDays' => $travelData['numberOfDays'],
                    'visibility' => TravelVisibilityEnum::PUBLIC
                ]
            );

            $travel->moods()->sync([]);

            foreach(Mood::active()->get() as $mood) {
                $value = random_int(1, 10) * 10;
                $travel->moods()->attach($mood->id, ['value' => $value]);
            }

        }
    }
}
