<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // get a random travel to associate with the tour
        $travelsId = Travel::pluck('id');

        $tours = [

            [
                "id" => "2a0edc99-c9fe-4206-8da5-413586667a21",
                "travelId" => $travelsId->random(),
                "name" => "ITJOR20211101",
                "startingDate" => "2021-11-01",
                "endingDate" => "2021-11-09",
                "price" => 199900
            ],
            [
                "id" => "7f0ff8cc-6b19-407e-9915-279ad76c0b5c",
                "travelId" => $travelsId->random(),
                "name" => "ITJOR20211112",
                "startingDate" => "2021-11-12",
                "endingDate" => "2021-11-20",
                "price" => 189900
            ],
            [
                "id" => "0be966b8-0a9b-4220-b9b2-e49d2cc0c2ab",
                "travelId" => $travelsId->random(),
                "name" => "ITJOR20211125",
                "startingDate" => "2021-11-25",
                "endingDate" => "2021-12-03",
                "price" => 214900
            ],
            [
                "id" => "94682e59-cbbd-44f5-861f-fb06c0ce18da",
                "travelId" => $travelsId->random(),
                "name" => "ITICE20211101",
                "startingDate" => "2021-11-01",
                "endingDate" => "2021-11-08",
                "price" => 199900
            ],
            [
                "id" => "90155d92-01e5-4c4b-a5a8-e24011fa8418",
                "travelId" => $travelsId->random(),
                "name" => "ITARA20211221",
                "startingDate" => "2021-12-21",
                "endingDate" => "2021-12-28",
                "price" => 189900
            ],
            [
                "id" => "9cefe1bc-eeb7-4d6d-b572-8a7aea2688d1",
                "travelId" => $travelsId->random(),
                "name" => "ITARA20211221",
                "startingDate" => "2022-01-03",
                "endingDate" => "2022-01-10",
                "price" => 149900
            ]
        ];

        foreach ($tours as $tour) {
            Tour::create($tour);
        }
    }
}
