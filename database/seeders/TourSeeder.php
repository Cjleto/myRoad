<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use App\Services\TourService;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function __construct(private TourService $tourService)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // get a random travel to associate with the tour
        $travels = Travel::all();

        $tours = [

            [
                'id' => '2a0edc99-c9fe-4206-8da5-413586667a21',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2021-11-02',
                'endingDate' => '2021-11-10',
                'price' => 1999,
            ],
            [
                'id' => '7f0ff8cc-6b19-407e-9915-279ad76c0b5c',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2021-11-12',
                'endingDate' => '2021-11-20',
                'price' => 1899,
            ],
            [
                'id' => '0be966b8-0a9b-4220-b9b2-e49d2cc0c2ab',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2021-11-25',
                'endingDate' => '2021-12-03',
                'price' => 2149,
            ],
            [
                'id' => '94682e59-cbbd-44f5-861f-fb06c0ce18da',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2021-11-01',
                'endingDate' => '2021-11-08',
                'price' => 1999,
            ],
            [
                'id' => '90155d92-01e5-4c4b-a5a8-e24011fa8418',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2021-12-21',
                'endingDate' => '2021-12-28',
                'price' => 1899,
            ],
            [
                'id' => '9cefe1bc-eeb7-4d6d-b572-8a7aea2688d1',
                'travelId' => '',
                'name' => '',
                'startingDate' => '2022-01-03',
                'endingDate' => '2022-01-10',
                'price' => 1499,
            ],
        ];

        $x = 0;
        foreach ($tours as $tour) {
            $randomTravel = $travels[$x % 3];
            $x++;
            $tour['travelId'] = $randomTravel->id;
            $tour['name'] = $this->tourService->generateName(
                $randomTravel,
                [
                    'startingDate' => $tour['startingDate'],
                    'endingDate' => $tour['endingDate'],
                ]);
            Tour::create($tour);
        }
    }
}
