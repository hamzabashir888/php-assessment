<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function register(Request $request)
    {
        // validating post parameters
        $rules =
        [
            'email' => ['required', 'email', 'max:199', 'unique:users'],
            'password' => 'required|string|confirmed|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        }
        $verificationCode=$this->quickRandom();
        $user=[
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'status'=>'deactive',
            'verification_code'=>$verificationCode
        ];
        $to = $request->email;
        $subject = "User Verification Email";
        $txt = $verificationCode;
        mail($to,$subject,$txt);
        $user=User::create($user);
        return response()->json(['status'=>200,'message'=>'User Saved Successfully. A verification code sent to the provided email.Please verify!','user'=>$user]);
    }
    // verifying user by email code
    public function verifyUser(Request $request)
    {
        $rules =
        [
            'user_id'=> 'required|integer',
            'code'=> 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        }
        $user = User::where('id',$request->user_id)->first();
        if($user){
            if (User::where('verification_code',$user->verification_code)->first()){
                 User::where('id',$request->user_id)->update(['status'=>"active"]);
                return response()->json(['status'=>200, 'message'=>'verified successfully']);
            } else {
                return response()->json(['status'=>100, 'message'=>'verification code is not correct']);
            }
        }else{
            return response()->json(['status'=>100, 'message'=>'user id doest not exists']);
        }
    }
     // random code function for verification
    public static function quickRandom($length = 6)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($str, 5)), 0, $length);
    }
}
