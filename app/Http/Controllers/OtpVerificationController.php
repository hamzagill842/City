<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendOtp;
use App\Http\Requests\VerifyOtp;
use App\Mail\OtpMail;
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

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Verify the OTP
        if ($request->has('otp') && $request->otp == $user->otp) {
            $token = JWTAuth::fromUser($user);
            $user->otp_verify = true;
            $user->otp = null;
            $user->save();
            return Response::success('OTP verified successfully', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
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
            $user = User::where('email', $request->email)->first();
            $user->otp = $otp;
            $user->save();
            Mail::to($user->email)->send(new OtpMail($otp));
           return Response::success('OTP resend successfully', [
                'message' => 'otp has been sent on your email, please check your email.'
            ], 200);
        } catch (Exception $exception) {
            return Response::failure('OTP resend failed', [
                'message' => 'failed.'
            ], 200);
        }
    }
}
