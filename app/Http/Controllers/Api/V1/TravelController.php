<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use App\Services\TravelService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class TravelController extends Controller
{
    public function __construct(private TravelService $travelService)
    {
    }

    public function index()
    {
        return TravelResource::collection(Travel::with('moods')->paginate(1));
    }

    /**
     * Store a new Travel
     *
     * Create a new travel
     *
     * @authenticated
     * @group Travel Endpoints
     *
     * @bodyParam moods string required The moods of the travel. Example: {"nature": 80,"relax": 20,"history": 90,"culture": 30,"party": 10}
     *
     * @response 201 {
     * "data": {
     *   "id": "9b1bff16-e089-41de-8d4f-9e87f9014139",
     *   "name": "Jordan 360",
     *   "slug": "jordan-360",
     *   "description": "Jordan 360°: the perfect tour to....",
     *   "numberOfDays": 8,
     *   "numberOfNight": 7,
     *   "moods": {
     *       "nature": 80,
     *       "relax": 20,
     *       "history": 90,
     *       "culture": 30,
     *       "party": 10
     *   }
     * }
     */
    public function store(StoreTravelRequest $request)
    {

        /* if(!auth()->user()->tokenCan('can_create_travels')) {
            return $this->failure(new UnauthorizedException('You are not authorized to create travels'), 403);
        } */

        $travel = $this->travelService->create($request);
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
     * @group Travel Endpoints
     *
     * @urlParam id uuid required The ID of the travel.<br> Example: d408be33-aa6a-4c73-a2c8-58a70ab2ba4d
     *
     * @bodyParam moods string required The moods of the travel. Example: {"nature": 80,"relax": 20,"history": 90,"culture": 30,"party": 10}
     *
    */
    public function update(UpdateTravelRequest $request, Travel $travel)
    {

        $travel = $this->travelService->update($request, $travel);
        $travel->load('moods');

        return $this->success(new TravelResource($travel));
    }
}
