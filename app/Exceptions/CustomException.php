<?php

namespace App\Exceptions;

use Exception;

/** @phpstan-consistent-constructor */

class CustomException extends Exception
{

    public function render($request)
    {

        if ($request->is('api/*')) {

            $data = [
                'success' => false,
                'message' => $this->getMessage(),
            ];

            if (config('app.debug')) {
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

    public function report()
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

    public static function unauthorized(string $message = 'You are not authorized to perform this action', int $code = 403)
    {
        return new self($message, $code);
    }

    public static function unprocessableContent(string $message = 'Bad request, something goes wrong', int $code = 422)
    {
        return new self($message, $code);
    }
}
