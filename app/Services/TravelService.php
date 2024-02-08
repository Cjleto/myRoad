<?php

namespace App\Services;

use App\Enums\TravelVisibilityEnum;
use App\Exceptions\TravelAlreadyExistsException;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Models\Mood;
use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelService extends BaseService
{
    public function create(StoreTravelRequest $request): mixed
    {

        $travelExists = self::checkTravelAlreadyExists($request);

        if ($travelExists) {
            throw new TravelAlreadyExistsException('Travel already exists');
        }

        DB::beginTransaction();

        $fillableData = self::getFillableAttributes($request);

        try {
            $travel = Travel::create($fillableData);

            foreach ($request->moods as $moodName => $moodValue) {
                $mood = Mood::where('name', $moodName)->first();
                if ($mood) {
                    $travel->moods()->attach($mood->id, ['value' => $moodValue]);
                }
            }

            if ($images = $request->file('images')) {
                foreach ($images as $image) {
                    $travel->addMedia($image)->toMediaCollection('images');
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            activity()
                ->useLog('travel-store')
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $request->all(), 'exception' => $e->getMessage()])
                ->log('Error while creating travel');

            throw $e;
        }

        return $travel;
    }

    public function update(UpdateTravelRequest $request, Travel $travel): Travel
    {

        DB::beginTransaction();

        try {

            $fillableData = self::getFillableAttributes($request);

            $travel->update($fillableData);

            if ($request->has('moods')) {
                foreach ($request->moods as $moodName => $moodValue) {
                    $mood = Mood::where('name', $moodName)->first();
                    if ($mood) {
                        $travel->moods()->syncWithoutDetaching([$mood->id => ['value' => $moodValue]]);
                    }
                }
            }

            if ($images = $request->file('images')) {
                $travel->clearMediaCollection('images');
                foreach ($images as $image) {
                    $travel->addMedia($image)->toMediaCollection('images');
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            activity()
                ->useLog('travel-update')
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $request->all(), 'exception' => $e->getMessage()])
                ->log('Error while updating travel');

            throw $e;
        }

        return $travel;
    }

    private function checkTravelAlreadyExists($data)
    {

        if (! config('myconstants.travels.unique_name')) {
            return false;
        }

        // if $data is an instance of StoreTravelRequest
        if ($data instanceof StoreTravelRequest) {
            $data = $data->validated();
        } else {
            $data = (array) $data;
        }

        $travel = Travel::where('name', $data['name'])
            ->first();

        return boolval($travel);

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
