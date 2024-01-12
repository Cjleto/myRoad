<?php

namespace App\Models;

use App\Enums\TravelVisibilityEnum;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Travel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'travels';

    protected $fillable = [
        'name',
        'description',
        'numberOfDays',
        'public',
    ];

    protected $casts = [
        'public' => TravelVisibilityEnum::class,
        'numberOfDays' => 'integer',
    ];

    protected $appends = [
        'numberOfNight',
    ];

    // set mutator for slug field to generate unique slug starting from name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function getNumberOfNightAttribute()
    {

        return $this->attributes['numberOfNight'] = $this->numberOfDays >= 1 ?
            $this->numberOfDays - 1 :
            0 ;
    }

    ## Scopes ##
    public function scopePublicOnly($query)
    {
        return $query->where('public', TravelVisibilityEnum::PUBLIC);
    }


    ## Relations ##
    public function moods (): BelongsToMany
    {
        return $this->belongsToMany(Mood::class,'mood_travel', 'travelId','moodId')
            ->withPivot('value');
    }
}
