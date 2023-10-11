<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Mail\OtpMail;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        try {
            DB::beginTransaction();
            // Validation passed if you reach this point
            $otp = Str::random(6);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'otp' => $otp,
                'date_of_birth' => $request->date_of_birth,
                'password' => bcrypt($request->password),
            ]);
            Mail::to($user->email)->queue(new OtpMail($otp));
            DB::commit();
            $user['next'] = 'otp';
            return Response::success('Operation succeeded', $user, 200);
         } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

    }

    public function signin(SignInRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();
        $next = $user->otp_verify;

        if ($next) {
           $data =  [
                 'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'otp_verify' => (bool)$user->otp_verify,
            ],
                'access_token' => $token,
            ];
            return Response::success('Operation succeeded', $data,200);
        } else {
            $error =  [
                'message' => 'Please verify Your OTP'
            ];
            return Response::failure('Operation failed', $error,401);
        }

    }


    public function logout()
    {
        try {

            JWTAuth::invalidate(JWTAuth::getToken());

            return Response::success('Operation succeeded', ['message' => 'User has been logout'],200);
        } catch (\Exception $e) {

            return Response::failure('Operation failed', ['error' => 'failed out '],200);
        }
    }

}
