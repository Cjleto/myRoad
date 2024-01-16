<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponses;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        // route model binding, force forrmat defined in custom exception
        if ($e instanceof ModelNotFoundException) {
            $modelName = Str::title(class_basename($e->getModel()));

            throw new TravelNotFound("Does not exists any {$modelName} with the specified identificator", 404);
        }

        return parent::render($request, $e);
    }
}
