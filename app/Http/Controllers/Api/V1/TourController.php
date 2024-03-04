<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\TravelDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Travel;
use App\Services\TourService;

class TourController extends Controller
{
    public function __construct(private TourService $tourService)
    {
    }

    /**
     * Get tours by travel slug
     *
     * @group Tour Endpoints
     *
     * @authenticated
     *
     * @urlParam travel_slug required The slug of the travel. Example: united-arab-emirates-from-dubai-to-abu-dhabi
     *
     * @response 200 {
     *    "data": [
     *        {
     *            "id": "7f0ff8cc-6b19-407e-9915-279ad76c0b5c",
     *            "travelId": "cbf304ae-a335-43fa-9e56-811612dcb601",
     *            "name": "ITUNI20240112",
     *            "startingDate": "2024-01-12",
     *            "endingDate": "2024-01-20",
     *            "price": 1899
     *        },
     *        {
     *            "id": "90155d92-01e5-4c4b-a5a8-e24011fa8418",
     *            "travelId": "cbf304ae-a335-43fa-9e56-811612dcb601",
     *            "name": "ITUNI20240121",
     *            "startingDate": "2024-01-21",
     *            "endingDate": "2024-01-28",
     *            "price": 1899
     *        }
     *    ],
     *    "links": {
     *        "first": "http://localhost:8009/api/v1/travel/united-arab-emirates-from-dubai-to-abu-dhabi/tours?sortBy=price&sortOrder=desc&priceFrom=10&page=1",
     *        "last": "http://localhost:8009/api/v1/travel/united-arab-emirates-from-dubai-to-abu-dhabi/tours?sortBy=price&sortOrder=desc&priceFrom=10&page=1",
     *        "prev": null,
     *        "next": null
     *    },
     *    "meta": {
     *        "current_page": 1,
     *        "from": 1,
     *        "last_page": 1,
     *        "links": [
     *            {
     *                "url": null,
     *                "label": "&laquo; Previous",
     *                "active": false
     *            },
     *            {
     *                "url": "http://localhost:8009/api/v1/travel/united-arab-emirates-from-dubai-to-abu-dhabi/tours?sortBy=price&sortOrder=desc&priceFrom=10&page=1",
     *                "label": "1",
     *                "active": true
     *            },
     *            {
     *                "url": null,
     *                "label": "Next &raquo;",
     *                "active": false
     *            }
     *        ],
     *        "path": "http://localhost:8009/api/v1/travel/united-arab-emirates-from-dubai-to-abu-dhabi/tours",
     *        "per_page": 2,
     *        "to": 2,
     *        "total": 2
     *    }
     *}
     */
    public function toursByTravelSlug(Travel $travel, TourListRequest $request)
    {

        $tours = $this->tourService->getToursByTravelSlug($travel, $request);

        return TourResource::collection($tours);
    }

    public function index()
    {
        return TourResource::collection(Tour::latest()->get());
    }

    /**
     * Store a new Tour
     *
     * @authenticated
     *
     * @group Tour Endpoints
     *
     * @response 201 {
     *    "success": true,
     *    "data": {
     *        "id": "9b20f8e0-b615-4090-8702-e8bf7adedc82",
     *        "travelId": "4f4bd032-e7d4-402a-bdf6-aaf6be240d53",
     *        "name": "ITICE20211107",
     *        "startingDate": "2021-11-07",
     *        "endingDate": "2021-11-15",
     *        "price": 213
     *    }
     * }
     */
    public function store(StoreTourRequest $request)
    {

        if (! auth()->user()->tokenCan('can_create_tours')) {
            return $this->failure(CustomException::unauthorized('You are not authorized to create tours'), 403);
        }

        $tour = $this->tourService->create($request);

        return $this->success(new TourResource($tour), 201);

    }
}
