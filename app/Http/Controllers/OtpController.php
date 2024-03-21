<?php

namespace App\Http\Controllers;

use App\Models\UserOtps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public static function generate()
    {

        try {
            $user = Auth::user();            

            if ($user) {

                // Retrieve the last OTP generation timestamp for the user
                $lastOTP = UserOtps::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                    // Calculate the current time and the time of the last OTP generation
                $currentTime = now();
                $lastOTPTime = $lastOTP ? $lastOTP->created_at : null;

                // Calculate the time difference in seconds
                $timeDifference = $lastOTPTime ? $currentTime->diffInSeconds($lastOTPTime) : null;               

                if (!$lastOTP || $timeDifference >= 60) {                  

                    //Delete older request
                    UserOtps::where('user_id', $user->id)->delete();

                    //New OTP
                    $otp = new UserOtps;
                    $otp->user_id = Auth::user()->id;
                    $otp->save();                   

                    if ($otp) {
                        //code...

                        $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office. Ang OTP mo ay " . $otp->otp;
                        $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message=' . $msg . '&MobileNumbers=63' . substr(Auth::user()->mobile_number, 1));
                        $res = $response->collect();

                        

                        if (isset($res["ErrorCode"]) && $res["ErrorCode"] == 0) {
                            return [
                                "success" => "OTP Sent to " . Auth::user()->mobile_number . ". OTP will expire in 10 min",
                                "sms_response" =>  $res
                            ];
                        } else {
                            return response()->json(["message" => $res["ErrorDescription"]], 422);
                        }
                    }
                } else {
                    $remainingTime = 60 - $timeDifference;
                    // Return an error response indicating the user needs to wait
                    return response()->json(['message' => "Please wait for $remainingTime seconds before requesting a new OTP."], 400);
                }
            } else {
                // Handle case where user is not authenticated
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        /* Validation Logic */

        $userOtp   = UserOtps::where('user_id', Auth::user()->id)->where('otp', $request->otp)->first();


        if (!$userOtp) {
            return response()->json(["message" => 'Your OTP is not correct'], 422);
        } else if ($userOtp && now()->isAfter($userOtp->expire_at)) {
            return response()->json(["message" => 'This OTP has expired'], 422);
        }

        $user = User::whereId(Auth::user()->id)->first();

        if ($user) {
            $userOtp->expire_at = now();
            $userOtp->save();           

            $user->mobile_verified = 1;
            $user->save();

            UserOtps::where('user_id', $user->id)->delete();

            return ["success" => "OTP Verified"];
        }
    }
}
