<?php

namespace App\Http\Controllers\Api\V1;

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
     * @authenticated
     *
     * @urlParam travel_slug required The slug of the travel. Example: united-arab-emirates-from-dubai-to-abu-dhabi
     */
    public function toursByTravelSlug(Travel $travel, TourListRequest $request)
    {

        $tours = $travel->tours()
            ->when($request->has('priceFrom'), function ($query) use ($request) {
                $query->where('price', '>=', (int) $request->input('priceFrom') * 100);
            })
            ->when($request->has('priceTo'), function ($query) use ($request) {
                $query->where('price', '<=', (int) $request->input('priceTo') * 100);
            })
            ->when($request->has('dateFrom'), function ($query) use ($request) {
                $query->whereDate('startingDate', '>=', $request->input('dateFrom'));
            })
            ->when($request->has('dateTo'), function ($query) use ($request) {
                $query->whereDate('startingDate', '<=', $request->input('dateTo'));
            })
            ->when($request->has('sortBy') && $request->has('sortOrder'), function ($query) use ($request) {
                $query->orderBy($request->input('sortBy'), $request->input('sortOrder'));
            });

        $tours = $tours->orderBy('startingDate')
            ->paginate(config('myconstants.tours.paginate'))
            ->withQueryString();

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
     * @group Tour Endpoints
     *
     */
    public function store(StoreTourRequest $request)
    {

        $tour = $this->tourService->create($request);

        return $this->success(new TourResource($tour), 201);

    }
}
