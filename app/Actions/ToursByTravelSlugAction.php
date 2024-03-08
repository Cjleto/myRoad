<?php

namespace App\Actions;

use App\Models\Travel;
use App\Traits\CacheHandler;
use App\Services\TourService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ToursByTravelSlugAction
{

    use CacheHandler;

    public function __construct(private TourService $tourService)
    {
    }

    public function execute(Travel $travel, Request $request): LengthAwarePaginator
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
