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
        return $this->success(TravelResource::collection(Travel::with('moods')->get()));
    }

    /**
     * Post Travel
     *
     * Create a new travel
     *
     * @authenticated
     * @group Travel Endpoints
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
     * PATCH Travel
     *
     * Update a travel
     *
     * @authenticated
     * @group Travel Endpoints
     *
     * @urlParam id uuid required The ID of the travel.<br> Example: 9b1b5239-ff84-4876-a83a-9213199bd50b
     *
     * @bodyParam name string required The name of the tour.
     * @bodyParam description string required The detailed description of the tour.
     * @bodyParam numberOfDays integer required The number of days for the tour.
    * @bodyParam moods object[] required An array of moods associated with the tour. Example: [{"nature": 80, "relax": 20, "history": 90, "culture": 10, "party": 80}]
    * @bodyParam moods[].nature integer required The nature mood value. Example: 80
    * @bodyParam moods[].relax integer required The relax mood value. Example: 20
    * @bodyParam moods[].history integer required The history mood value. Example: 90
    * @bodyParam moods[].culture integer required The culture mood value. Example: 10
    * @bodyParam moods[].party integer required The party mood value. Example: 80
     * @bodyParam visibility string required The visibility of the tour (public or private). Example: public
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
    public function update(UpdateTravelRequest $request, Travel $travel)
    {

        $travel = $this->travelService->update($request, $travel);
        $travel->load('moods');

        return $this->success(new TravelResource($travel));
    }
}
