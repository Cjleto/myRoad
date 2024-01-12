<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'travelId',
        'name',
        'startingDate',
        'endingDate',
        'price',
    ];

    public function travel ()
    {
        return $this->belongsTo(Travel::class, 'travelId');
    }
}
