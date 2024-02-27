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
        if (Auth::user()) {
            $otp = new UserOtps;
            $otp->user_id = Auth::user()->id;
            $otp->save();

            if ($otp) 
            {
                $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office. Ang OTP mo ay ". $otp->otp ;
                $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message=' . $msg . '&MobileNumbers=63' . substr(Auth::user()->mobile_number, 1));
                $res = $response->collect();        
                     
                if(isset($res["ErrorCode"]) && $res["ErrorCode"] == 0)
                {
                    return [
                        "success" => "OTP Sent to " . Auth::user()->mobile_number . ". OTP will expire in 15 min",
                        "sms_response" =>  $res
                    ];
                }else
                {
                    return response()->json(["message" =>$res["ErrorDescription"] ], 422);
                }
            }

           
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

            //Auth::login($user);
           

            return ["success" => "OTP Verified"];
        }
    }
}
