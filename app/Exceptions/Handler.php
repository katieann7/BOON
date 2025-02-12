<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    "response" => "Route not found! Check route path or parameter(s).",
                    'exception' => $e->getMessage()
                ],
                'data' => []
            ], 404)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache')
                ->header('X-Content-Type-Options', 'nosniff');;
        });

        $this->renderable(function (ValidationException $e, Request $r) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    "response" => $e->getMessage(),
                    'exception' => $e->errors()
                ],
                'data' => $r->all()
            ], 400)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache')
                ->header('X-Content-Type-Options', 'nosniff');;
        });
    }
}
