<?php

namespace App\Filters\Travels;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPriceFrom
{

    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, Closure $next)
    {
        if ($this->request->has('priceFrom')) {
            $builder->where('price', '>=', (int) $this->request->priceFrom * 100);
        }

        return $next($builder);
    }
}
