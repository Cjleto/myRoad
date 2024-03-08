<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Services\TourService;

class TourController extends Controller
{
    public function __construct(private TourService $tourService)
    {
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
