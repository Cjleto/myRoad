<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomException extends Exception
{
    public function render ($request)
    {

        if ($request->is('api/*')) {

            $data = [
                'success' => false,
                'message' => $this->getMessage(),
            ];

            if(config('app.debug'))
            {
                $data['file'] = $this->getFile();
                $data['line'] = $this->getLine();
                $data['trace'] = $this->getTrace();
            }

            return response()->json(
                $data,
                self::getCode()
            );
        }

    }

    public function report ()
    {
        activity()
            ->useLog(class_basename($this))
            ->withProperties(
                [
                    'level' => 'error',
                    'exception' => $this->getMessage(),
                    'request' => request()->path(),
                ]
            )
            ->causedBy(auth()->user() ?? null)
            ->log($this->getMessage());
    }
}
