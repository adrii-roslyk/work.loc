<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::create($request->validated());
        $data = UserResource::make($user)->toArray($request) +
            ['access_token' => $user->createToken('api')->plainTextToken];
        return $this->created($data);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth()->once($request->validated())) {
            throw ValidationException::withMessages([
                'email' => 'Wrong Email or Password',
            ]);
        }
        /** @var User $user */
        $user = auth()->user();
        $data =  UserResource::make($user)->toArray($request) +
            ['api_token' => $user->createToken('api')->plainTextToken];
        return $this->success($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return $this->success('User logged out');
    }
}
