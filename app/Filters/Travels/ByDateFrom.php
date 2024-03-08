<?php

namespace App\Filters\Travels;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByDateFrom
{

    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, Closure $next)
    {
        if ($this->request->has('dateFrom')) {
            $builder->where('startingDate', '>=', $this->request->dateFrom);
        }

        return $next($builder);
    }
}
