<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Trait Response
    // Use case:
    // Not encapsulated in Resource or Collection
    public function successResponse(string $message = null, $data, $code = Response::HTTP_OK)
    {
        return response()
            ->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ], $code)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'no-cache')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    // Use case:
    // For returning error validation
    public function errorResponse($message, $data = null, $code)
    {
        return response()
            ->json([
                'status' => 'error',
                'message' => $message,
                'data' => $data
            ], $code)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'no-cache')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function parseParameter($value)
    {
        if (is_numeric($value)) {
            return (int) $value; // Parse as numeric
        } else {
            return (string) strtoupper($value); // Parse as string
        }
    }

    public function getExceptionModel(ModelNotFoundException $exception)
    {
        $modelException = (string) $exception->getModel();
        $className = class_basename($modelException);
        $words = explode("\\", $className);
        $rawModelName = end($words);
        $modelName = preg_replace('/(?<!^)([A-Z])/', ' $1', $rawModelName);

        return $modelName;
    }

    public function modelExceptionResponse(ModelNotFoundException $exception, $data = null, $code = Response::HTTP_NOT_FOUND)
    {
        $model = ($this->getExceptionModel($exception) == "") ? $exception->getMessage() : $this->getExceptionModel($exception);
        return $this->errorResponse(
            [
                "response" => $model . "(s) not found!",
                "exception" => "No query results for " . $model
            ],
            $data,
            $code
        );
    }

    public function validationExceptionReponse(ValidationException $exception, $data = [])
    {
        return $this->errorResponse(
            [
                "response" => "Invalid body or parameter!",
                "errors" => $exception->errors(),
            ],
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
