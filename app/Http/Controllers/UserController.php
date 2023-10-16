<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function getUser()
    {
        $user = auth()->user();

        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'otp_verify' => (bool)$user->otp_verify,
            'city' => $user->city,
            'date_of_birth' => $user->date_of_birth,
        ];

        return Response::success('Operation succeeded', $user,200);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = auth()->user();

        // Update the user's information
        $user->update([
            'name' => $request->input('name') ?? $user->name,
//            'email' => $request->input('email') ?? $user->email,
            'city' => $request->input('city') ?? $user->city,
            'date_of_birth' => $request->input('date_of_birth') ?? $user->date_of_birth,
            'bio' => $request->input('bio') ?? $user->bio ,
            // Update other fields here
        ]);

        return Response::success('Operation succeeded', $user,200);
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        $user->tokens->each(function (PersonalAccessToken $token) {
            $token->delete();
        });
        $token = JWTAuth::fromUser($user);

        return Response::success('OTP verified successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'otp_verify' =>  (bool)$user->otp_verify,
            ],
            'access_token' => $token
        ],200);

    }
}
