<?php

namespace App\Http\Controllers;

use App\Models\UserOtps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function generate()
    {
        if (Auth::user()) {
            $otp = new UserOtps;
            $otp->user_id = Auth::user()->id;
            $otp->save();
            return $otp;
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

            return ["errors" => 'Your OTP is not correct'];
        } else if ($userOtp && now()->isAfter($userOtp->expire_at)) {

            return ["errors" => 'Your OTP has been expired'];
        }


        $user = User::whereId($request->user_id)->first();



        if ($user) {
            $userOtp->update([
                'expire_at' => now()
            ]);

            $user->mobile_verified = 1;
            $user->save();

            //Auth::login($user);

            return ["success"=> "OTP Verified"];
        }
    }
}
