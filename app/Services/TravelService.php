<?php

namespace App\Services;

use App\Models\Mood;
use App\DTO\TravelDTO;
use App\Models\Travel;
use App\DTO\CreateTravelDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\TravelVisibilityEnum;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\TravelAlreadyExistsException;

class TravelService extends BaseService
{
    public function create(CreateTravelDTO $createTravelDTO): Travel
    {

        $travelExists = self::checkTravelAlreadyExists($createTravelDTO);

        if ($travelExists) {
            throw new TravelAlreadyExistsException('Travel already exists');
        }

        DB::beginTransaction();

        try {
            $travel = Travel::create($createTravelDTO->getTravelFields());

            foreach ($createTravelDTO->moods as $moodName => $moodValue) {
                $mood = Mood::where('name', $moodName)->first();
                if ($mood) {
                    $travel->moods()->attach($mood->id, ['value' => $moodValue]);
                }
            }

            if (is_countable($createTravelDTO->images)) {
                foreach ($createTravelDTO->images as $image) {
                    $travel->addMedia($image)->toMediaCollection('images');
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            activity()
                ->useLog('travel-store')
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $createTravelDTO, 'exception' => $e->getMessage()])
                ->log('Error while creating travel');

            throw $e;
        }

        return $travel;
    }

    public function update(CreateTravelDTO $createTravelDTO, Travel $travel): Travel
    {

        DB::beginTransaction();

        try {

            $travel->update($createTravelDTO->getTravelFields());

            if(count($createTravelDTO->moods) > 0){
                foreach ($createTravelDTO->moods as $moodName => $moodValue) {
                    $mood = Mood::where('name', $moodName)->first();
                    if ($mood) {
                        $travel->moods()->syncWithoutDetaching([$mood->id => ['value' => $moodValue]]);
                    }
                }
            }

            if(is_countable($createTravelDTO->images)){
                $travel->clearMediaCollection('images');
                foreach ($createTravelDTO->images as $image) {
                    $travel->addMedia($image)->toMediaCollection('images');
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            activity()
                ->useLog('travel-update')
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $createTravelDTO->toArray(), 'exception' => $e->getMessage()])
                ->log('Error while updating travel');

            throw $e;
        }

        return $travel;
    }

    private function checkTravelAlreadyExists(CreateTravelDTO $data)
    {

        if (! config('myconstants.travels.unique_name')) {
            return false;
        }

        $travel = Travel::where('name', $data->name)
            ->exists();

        return $travel;

    }

    /**
     * Get fillable attributes from request
     */
    private function getFillableAttributes(FormRequest $request)
    {
        $fillable = array_filter($request->validated(), function ($key) {
            return in_array($key, (new Travel)->getFillable());
        }, ARRAY_FILTER_USE_KEY);

        return $fillable;
    }

    public static function generateCode(string $name, int $lenght = 3): string
    {
        // check if code already exists in database, if exists add a random number to the end
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name);

        $travelExists = true;
        while ($travelExists) {

            if ($lenght > strlen($name)) {
                $name .= rand(0, 9);
            }

            $code = strtoupper(substr($name, 0, $lenght));
            $travelExists = Travel::where('code', $code)->exists();
            $lenght++;
        }

        return $code;

    }

    public static function checkPublic(Travel $travel): bool
    {
        return $travel->visibility === TravelVisibilityEnum::PUBLIC;
    }
}
