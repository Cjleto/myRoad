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

    public function update(UpdateTravelRequest $request, Travel $travel)
    {

        $travel = $this->travelService->update($request, $travel);
        $travel->load('moods');

        return $this->success(new TravelResource($travel));
    }
}
