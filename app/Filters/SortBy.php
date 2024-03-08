<?php

namespace App\Filters;

class SortBy
{
    public function __construct(protected ?string $sortBy, protected ?string $sortOrder)
    {
        $this->sortOrder = $sortOrder ?? 'asc';
    }

    public function handle($builder, $next)
    {
        if ($this->sortBy && $this->sortOrder) {

            $builder->orderBy($this->sortBy, $this->sortOrder);
        }

        return $next($builder);
    }
}
