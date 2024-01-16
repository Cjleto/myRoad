<?php

namespace App\Models;

use App\Enums\ActiveEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tour extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $fillable = [
        'travelId',
        'name',
        'startingDate',
        'endingDate',
        'price',
    ];

    //# Accessors and Mutators ##
    protected function price(): Attribute
    {
        return Attribute::make(
            //get: fn (int $value) => ($value / 100),
            set: fn (int $value) => $value * 100,
        );
    }

    public function getPriceAttribute()
    {
        return $this->attributes['price'] / 100;
    }

    //# Activity Log Options ##
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable);
    }

    //# RELATIONSHIPS ##
    public function travel()
    {
        return $this->belongsTo(Travel::class, 'travelId');
    }

    //# SCOPES ##
    public function scopeActive(Builder $builder)
    {
        $builder->where('active', ActiveEnum::YES);
    }
}
