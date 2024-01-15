<?php

namespace App\Services;

use App\Models\Mood;
use App\Models\Travel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Exceptions\TravelAlreadyExistsException;

class TravelService extends BaseService
{
    public function create (StoreTravelRequest $request): mixed
    {

        $travelExists = self::checkTravelAlreadyExists($request);

        if($travelExists) {
            throw new TravelAlreadyExistsException('Travel already exists');
        }

        DB::beginTransaction();


        $fillableData = self::getFillableAttributes($request);

        try {
            $travel = Travel::create($fillableData);

            if($travel) {
                foreach($request->moods as $moodName => $moodValue) {
                    $mood = Mood::where('name', $moodName)->first();
                    if($mood) {
                        $travel->moods()->attach($mood->id, ['value' => $moodValue]);
                    }
                }
            } else {
                throw new \Exception('Error while creating travel');
            }

            DB::commit();


            self::logTravel($request, $travel);

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

    public function update (UpdateTravelRequest $request, Travel $travel): Travel
    {

        DB::beginTransaction();

        try {

            $fillableData = self::getFillableAttributes($request);

            $travel->update($fillableData);

            if($travel) {
                if(request()->has('moods')){
                    foreach($request->moods as $moodName => $moodValue) {
                        $mood = Mood::where('name', $moodName)->first();
                        if($mood) {
                            $travel->moods()->syncWithoutDetaching([$mood->id => ['value' => $moodValue]]);
                        }
                    }
                }
            } else {
                throw new \Exception('Error while updating travel');
            }

            DB::commit();

            self::logTravel($request, $travel);

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

    private function checkTravelAlreadyExists ($data)
    {

        // if $data is an instance of StoreTravelRequest
        if($data instanceof StoreTravelRequest) {
            $data = $data->validated();
        } else {
            $data = (array) $data;
        }

        $travel = Travel::where('name', $data['name'])
            ->orWhere('slug', Str::slug($data['name']))
            ->first();

        return boolval($travel);

    }

    private function logTravel (Request $request, Travel $travel)
    {

        $method = $request->method();

        activity()
                ->useLog('travel')
                ->event($method)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($travel)
                ->log('Travel created');
    }

    /**
     * Get fillable attributes from request
     */
    private function getFillableAttributes (Request $request)
    {
        $fillable = array_filter($request->validated(), function($key) {
            return in_array($key, (new Travel())->getFillable());
        }, ARRAY_FILTER_USE_KEY);

        return $fillable;
    }

}
