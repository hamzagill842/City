<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        // Validation passed if you reach this point
       $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'password' => bcrypt($request->password),
        ]);
        return Response::success('Operation succeeded', $user,200);
    }

    public function signin(SignInRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
       $data =  $this->respondWithToken($token);
        return Response::success('Operation succeeded', $data,200);

    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }

}
