<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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

    /**
     * Format errors.
     */
    protected function invalidJson($request, ValidationException $exception)
    {

        $title = $exception->getMessage();

        return new JsonResponse([
            'errors' => collect($exception->errors())->map(fn ($message, $field) => [
                'title' => $title,
                'detail' => $message[0],
                'source' => [
                    'pointer' => '/' . str_replace('.', '/', $field)
                ]

            ])->values()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
