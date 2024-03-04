<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\TravelDTO;
use App\Models\Travel;
use App\DTO\CreateTravelDTO;
use App\Services\TravelService;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;

class TravelController extends Controller
{
    public function __construct(private TravelService $travelService)
    {
    }

    public function index()
    {
        return TravelResource::collection(Travel::with('moods')->latest()->paginate(5));
    }

    /**
     * Store a new Travel
     *
     * Create a new travel
     *
     * @authenticated
     *
     * @header Content-Type multipart/form-data
     *
     * @group Travel Endpoints
     *
     * @bodyParam moods string required The moods of the travel. Example: {'nature': 80,'relax': 20,'history': 90,'culture': 30,'party': 10}
     * @bodyParam images file[] List of file. No-example
     *
     * @response 201 {
     *    "success": true,
     *    "data": {
     *      "id": "9b20f41b-0c51-4cc1-aa08-5c076876b356",
     *      "name": "new trrascve",
     *      "slug": "new-trrascve-9",
     *      "description": "new descriptrioin asdkj haskjdh jaksd",
     *      "numberOfDays": 4,
     *      "numberOfNight": 3,
     *      "moods": {
     *        "nature": 80,
     *        "relax": 20,
     *        "history": 90,
     *        "culture": 30,
     *        "party": 10
     *      },
     *      "images": [{
     *        "url": "http://localhost:8009/storage/9/Screenshot-2024-01-16-alle-13.01.46.png",
     *        "name": "Screenshot 2024-01-16 alle 13.01.46",
     *        "size": 170087,
     *        "mime_type": "image/png"
     *      }, {
     *        "url": "http://localhost:8009/storage/10/Screenshot-2023-12-07-alle-16.10.03.png",
     *        "name": "Screenshot 2023-12-07 alle 16.10.03",
     *        "size": 88690,
     *        "mime_type": "image/png"
     *      }]
     *    }
     * }
     */
    public function store(StoreTravelRequest $request)
    {

        if (! auth()->user()->tokenCan('can_create_travels')) {
            return $this->failure(CustomException::unauthorized('You are not authorized to create travels'), 403);
        }

        $createTravelDTO = CreateTravelDTO::fromRequest($request);

        $travel = $this->travelService->create($createTravelDTO);
        $travel->load('moods');

        return $this->success(new TravelResource($travel), 201);

    }

    public function show(Travel $travel)
    {
        $travel->load('moods');

        return $this->success(new TravelResource($travel));
    }

    /**
     * Update a Travel
     *
     * Update a travel
     *
     * @authenticated
     *
     * @group Travel Endpoints
     *
     * @urlParam id uuid required The ID of the travel.<br> Example: d408be33-aa6a-4c73-a2c8-58a70ab2ba4d
     *
     * @bodyParam moods string required The moods of the travel. Example: {'nature': 80,'relax': 20,'history': 90,'culture': 30,'party': 10}
     * @bodyParam images file[] List of file. It will replace all the existing images No-example
     *
     * @response 201 {
     *    "success": true,
     *    "data": {
     *      "id": "9b20f41b-0c51-4cc1-aa08-5c076876b356",
     *      "name": "new trrascve",
     *      "slug": "new-trrascve-9",
     *      "description": "new descriptrioin asdkj haskjdh jaksd",
     *      "numberOfDays": 4,
     *      "numberOfNight": 3,
     *      "moods": {
     *        "nature": 80,
     *        "relax": 20,
     *        "history": 90,
     *        "culture": 30,
     *        "party": 10
     *      },
     *      "images": [{
     *        "url": "http://localhost:8009/storage/9/Screenshot-2024-01-16-alle-13.01.46.png",
     *        "name": "Screenshot 2024-01-16 alle 13.01.46",
     *        "size": 170087,
     *        "mime_type": "image/png"
     *      }, {
     *        "url": "http://localhost:8009/storage/10/Screenshot-2023-12-07-alle-16.10.03.png",
     *        "name": "Screenshot 2023-12-07 alle 16.10.03",
     *        "size": 88690,
     *        "mime_type": "image/png"
     *      }]
     *    }
     * }
     */
    public function update(UpdateTravelRequest $request, Travel $travel)
    {

        if (! auth()->user()->tokenCan('can_update_travels')) {
            return $this->failure(CustomException::unauthorized('You are not authorized to update travels'), 403);
        }

        $createTravelDTO = CreateTravelDTO::fromRequest($request);

        $travel = $this->travelService->update($createTravelDTO, $travel);
        $travel->load('moods');

        return $this->success(new TravelResource($travel));
    }
}
