<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function store (StoreTourRequest $request)
    {
        dd($request->validated());
    }
}
