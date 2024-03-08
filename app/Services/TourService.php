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

}
