<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Mail;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $postData = $request->json()->all();

        $user = User::where('email', $postData['email'])->first();
        // CHECK IF USER EXISTS
        if(!$user){
            // CREATE NEW USER
            $user = User::create([
                'name' => $postData['email'],
                'email' => $postData['email'],
                'password' => bcrypt($postData['password']),
            ]);
        }
        $data['four_digit_code'] = mt_rand(1111,9999);

        // SEND 4 DIGIT CODE TO EMAIL
        Mail::send('email', $data, function ($mail) use ($postData) {
            $mail->from("no-reply@school.com", 'School Management System');
            $mail->to($postData['email'], '')->subject('Login Code');
        });

        $token = $user->createToken('auth_token')->plainTextToken;
        
        // SEND TOKEN IN RESPONSE
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
