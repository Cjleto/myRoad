<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreTourRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Traits\CacheHandler;

class TourService
{
    use CacheHandler;
    public function paginate(): mixed
    {
        $tours = Tour::simplePaginate(config('myconstants.tours.paginate') ?? 3);

        return $tours;

    }

    public function create(StoreTourRequest $request): mixed
    {

        $travel = Travel::findOrFail($request->travelId);
        $data = $request->validated();
        /* $data['price'] *= 100; */
        $data['name'] = self::generateName($travel, $request->validated());

        self::checkTourAlreadyExists($data);

        $tour = Tour::create($data);

        return $tour;
    }

    public function validCreatingPeriod(Travel $travel, StoreTourRequest $request): bool
    {
        $startingDate = Carbon::parse($request->startingDate);
        $endingDate = Carbon::parse($request->endingDate);

        $period = $startingDate->diffInDays($endingDate);

        return $period == $travel->numberOfDays;
    }

    public function generateName(Travel $travel, $request): string
    {
        $travelCode = 'IT';
        $travelCode .= $travel->code;
        $travelCode .= Carbon::parse($request['startingDate'])->format('Ymd');

        return $travelCode;
    }

    private static function checkTourAlreadyExists(array $data): void
    {
        $tour = Tour::where('name', $data['name'])->first();

        if ($tour) {
            throw CustomException::unprocessableContent("Tour with name '".$data['name']."' already exists");
        }
    }

    public function getToursByTravelSlug(Travel $travel, Request $request): LengthAwarePaginator
    {

        $rememberKey = $this->getCacheKeyFromRequest($request);

        return Cache::remember($rememberKey, 150, function () use ($request, $travel, $rememberKey) {

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

            info('put in cache: '.$rememberKey);

            return $tours;
        });



    }
}
