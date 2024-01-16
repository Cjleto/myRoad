<?php

namespace App\Models;

use App\Enums\TravelVisibilityEnum;
use App\Services\TravelService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Travel extends Model
{
    use HasFactory, HasUuids, LogsActivity, Sluggable;

    protected $table = 'travels';

    protected $fillable = [
        'name',
        'description',
        'numberOfDays',
        'visibility',
    ];

    protected $casts = [
        'visibility' => TravelVisibilityEnum::class,
        'numberOfDays' => 'integer',
    ];

    public static $snakeAttributes = false;

    protected $appends = [
        'numberOfNight',
    ];

    //# sluggable attribute ##
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => '-',
                'onUpdate' => true,
            ],
        ];
    }

    //# Activity Log Options ##
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable);
    }

    //# Mutators ##
    // set mutator for slug field to generate unique slug starting from name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        /* $this->attributes['slug'] = Str::slug($value, '-'); */
        $this->attributes['code'] = TravelService::generateCode($value);
    }

    public function getNumberOfNightAttribute()
    {

        return $this->attributes['numberOfNight'] = $this->numberOfDays >= 1 ?
            $this->numberOfDays - 1 :
            0;
    }

    //# Scopes ##
    public function scopePublicOnly($query)
    {
        return $query->where('visibility', TravelVisibilityEnum::PUBLIC);
    }

    //# Relations ##
    public function moods(): BelongsToMany
    {
        return $this->belongsToMany(Mood::class, 'mood_travel', 'travelId', 'moodId')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'travelId');
    }
}
