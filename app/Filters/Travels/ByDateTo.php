<?php

namespace App\Filters\Travels;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByDateTo
{

    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, Closure $next)
    {
        if ($this->request->has('dateTo')) {
            $builder->where('startingDate', '<=', $this->request->dateTo);
        }

        return $next($builder);
    }
}
