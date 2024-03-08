<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Http\Requests\TourListRequest;
use App\Actions\ToursByTravelSlugAction;

class ToursByTravelSlugController extends Controller
{
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
    public function toursByTravelSlug(Travel $travel, TourListRequest $request, ToursByTravelSlugAction $toursByTravelSlugAction)
    {

        $tours = $toursByTravelSlugAction->execute($travel, $request);

        return TourResource::collection($tours);
    }
}
