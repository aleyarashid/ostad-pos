<?php

namespace App\Http\Controllers;

use App\Http\Helper\JWTToken;
use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class UserController extends Controller
{
    //for user registration
    public function UserRegistration(Request $request){
        //validation
        try{

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
            //create user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);
            //return response
            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => $user,
            ], 200);

        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], 500);

        }
    }

    //for user login

    public function UserLogin(Request $request){

        $count = User::where('email', $request->input('email'))->where('password', $request->input('password'))->select('id')->first();   

        if($count !== null){

            //user login -> JWT token

            $token = JWTToken::CreateToken($request->input('email'), $count->id);

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'token' => $token,

            ], 200)->cookie('token', $token, 60*24*30);
            
        }

        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }

    }

    public function DashBoardPage(Request $request){
        $user = $request->header('email');
        return response()->json([
            'status' => 'success',
            'message' => 'user dashboard page',
            'user' => $user,
        ], 200);
    }
    public function UserLogout(Request $request){
        return response()->json([
            'status' => 'success',
            'message' => 'user logged out successfully',
        ], 200)->cookie('token', '', -1);
    }
    public function SendOtpCode(Request $request){
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $count = User::where('email', $email)->count();
        if($count > 0){
            Mail::to($email)->send(new OtpMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => '6 digit{$otp} otp sent successfully',
                'otp' => $otp,
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }
}
