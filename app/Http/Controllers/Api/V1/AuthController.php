<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Entities\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\AuthRequest;
use App\Http\Resources\V1\Entities\UserResource;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($user->username, ['delivery-module'])->accessToken;

            return $this->successResponse("User authorized!", [$token, new UserResource($user->load('costCenter'))]);
        } else {
            return $this->errorResponse("Credentials not found! Unauthorized.", $request->all(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
