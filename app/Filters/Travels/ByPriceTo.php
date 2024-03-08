<?php

namespace App\Filters\Travels;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPriceTo
{

    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, Closure $next)
    {
        if ($this->request->has('priceTo')) {
            $builder->where('price', '<=', (int) $this->request->priceTo * 100);
        }

        return $next($builder);
    }
}
