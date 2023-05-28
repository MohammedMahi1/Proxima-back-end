<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('register','login');
    }
    public function register (Request $request):Response
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:12',
            'type' => 'required|string',
        ]);
        if($validator->fails())
        {
            return Response([
               'status'=>401,
                'errors' =>$validator->errors()
            ],422);
        }
        $user = User::create([
           'name'=>$request->name,
            'email'=>$request->email,
           'password'=>Hash::make($request->password),
            'type' => $request->type,
        ]);
        if ($user)
        {
            try{
                Mail::mailer('smtp')->to($user->email)->send(new VerificationMail($user));
                return Response([
                    'status' =>200,
                    'message' =>'Registred, verified your email to login.',
                ],200);
            }
            catch (\Exception $err) {
                return Response([
                    'status' => 500,
                    'message' =>$err->getMessage(),
                ]);

            }
        }

    }
    public function login (Request $request):Response
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:12',
        ]);

        if (Auth::guard('user')->attempt($user)) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $device = $request->userAgent();
                $token = $user->createToken($device)->plainTextToken;
                return Response([
                    "status"=>200,
                    'token' => $token
                ]);
            }
        }
        return Response([
            'status' => 400,
            'message' => 'Your data is incorect'
        ]);
    }
    public function logout($token = null)
    {
        $user = Auth::guard('sanctum')->user();
        if (null == $token) {
            $user->currentAccessToken()->delete();
            return ;
        }
        $personaleToken = PersonalAccessToken::findToken($token);
        if ($user->id == $personaleToken->tokenable_id && get_class($user) == $personaleToken->tokenable_type) {
            $personaleToken->delete();
            return response()->json([
                'status'=>200,
                'message' => 'logout successful',
            ]);
        }

    }
}
