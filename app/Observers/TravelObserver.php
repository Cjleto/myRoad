<?php

namespace App\Observers;

use App\Models\Travel;
use App\Traits\CacheHandler;

class TravelObserver
{

    use CacheHandler;

    /**
     * Handle the Travel "updated" event.
     * Delete all the cache keys related to the travel
     */
    public function updated(Travel $travel): void
    {

        info('TravelObserver: '. $travel->slug);

        $rememberKey = "travel:".$travel->slug;

        $this->clearCacheContainingKey($rememberKey);


    }


}
