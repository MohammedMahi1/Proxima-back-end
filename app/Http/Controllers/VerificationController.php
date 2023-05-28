<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($id, Request $request)
    {
        if (!$request->hasValidSignature()) {

            return response()->json([
                'message'=>"Invalid/Expired url provided ."
            ],401);
        }
        $user = User::findorfail($id);
        if(!$user->hasVerifiedEmail()) {
            $user->verify = true;
            $user->markEmailAsVerified();
        }else{
            return response()->json([
               'status'=>400,
                'message'=>'Email has already verified !'
            ],400);
        }
        return response()->json([
           'status'=>200,
            'message'=>"Your email $user->email has been verified."
        ],200);
    }
}
