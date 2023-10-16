<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendOtp;
use App\Http\Requests\User\ResendPasswordRequest;
use App\Http\Requests\VerifyOtp;
use App\Mail\OtpMail;
use App\Models\PasswordReset;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use SebastianBergmann\Diff\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class OtpVerificationController extends Controller
{
    public function verifyOtp(VerifyOtp $request)
    {

        $user = User::where('email', $request->email)->first();
        $resetPassword = PasswordReset::where('email', $request->email)->first();

        if (!$resetPassword) {
            return response()->json(['error' => 'not found'], 404);
        }

        // Verify the OTP
        if ($request->has('otp') && $request->otp == $resetPassword->token) {
            $token = JWTAuth::fromUser($user);
            $user->otp_verify = true;
            $user->save();
            PasswordReset::where('email', $request->email)->delete();
            return Response::success('OTP verified successfully', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'otp_verify' =>  (bool)$user->otp_verify,
                ],
                'access_token' => $token
            ],200);

        } else {

            return Response::failure('Invalid OTP', ['error' => 'Invalid OTP'],400);
        }
    }


    public function resendOtp(ResendOtp $request)
    {
        try {

            $otp = Str::random(6);

            $condition = ['email' => $request->email];
            $values = ['token' => $otp];

            PasswordReset::updateOrInsert($condition, $values);

            Mail::to($request->email)->send(new OtpMail($otp));
           return Response::success('OTP resend successfully', [
                'message' => 'otp has been sent on your email, please check your email.'
            ], 200);
        } catch (Exception $exception) {
            return Response::failure('OTP resend failed', [
                'message' => 'failed.'
            ], 200);
        }
    }

    public function forgetPassword(ResendOtp $request)
    {
        try {

            $otp = Str::random(6);

            $condition = ['email' => $request->email];
            $values = ['token' => $otp];

            PasswordReset::updateOrInsert($condition, $values);

            Mail::to($request->email)->send(new OtpMail($otp));
           return Response::success('OTP resend successfully', [
                'message' => 'otp has been sent on your email, please check your email.'
            ], 200);
        } catch (Exception $exception) {
            return Response::failure('OTP resend failed', [
                'message' => 'failed.'
            ], 200);
        }
    }

    public function resetPassword(ResendPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $resetPassword = PasswordReset::where('email', $request->email)->first();

        if (!$resetPassword) {
            return response()->json(['error' => 'not found'], 404);
        }

        // Verify the OTP
        if ($request->has('otp') && $request->otp == $resetPassword->token) {
            $token = JWTAuth::fromUser($user);
            $user->otp_verify = true;
            $user->save();
            PasswordReset::where('email', $request->email)->delete();
            return Response::success('OTP verified successfully', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'otp_verify' =>  (bool)$user->otp_verify,
                ],
                'access_token' => $token
            ],200);

        } else {

            return Response::failure('Invalid OTP', ['error' => 'Invalid OTP'],400);
        }
    }


}
