<?php

namespace App\Actions;

use App\Filters\SortBy;
use App\Filters\Travels\ByDateFrom;
use App\Filters\Travels\ByDateTo;
use App\Models\Travel;
use App\Traits\CacheHandler;
use Illuminate\Http\Request;
use App\Services\TourService;
use App\Filters\Travels\ByPriceTo;
use App\Filters\Travels\ByPriceFrom;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Pipeline;
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

        $pipeline = [

        ];

        return Cache::remember($rememberKey, 1, function () use ($request, $travel, $rememberKey) {

            $pipeline = [
                ByPriceFrom::class,
                ByPriceTo::class,
                ByDateFrom::class,
                ByDateTo::class,
                new SortBy($request->input('sortBy'), $request->input('sortOrder'))
            ];

            $tours = Pipeline::send(
                    $travel->tours()
                        ->getQuery()
                )
                ->through($pipeline)
                ->thenReturn()
                ->orderBy('startingDate')
                ->paginate(config('myconstants.tours.paginate'))
                ->withQueryString();

            info('put in cache: '.$rememberKey);

            return $tours;
        });
    }
}
