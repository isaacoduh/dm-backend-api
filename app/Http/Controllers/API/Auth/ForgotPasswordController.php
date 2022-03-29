<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    protected function sendResetLinkResponse(Request $request)
    {
        $input = $request->only('email');
        $validator = Validator::make($input,[
            'email' => 'required|email|exists:users'
        ]);

        

        if($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgotPassword', ['token' => $token], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });



        // $response = Password::sendResetLink($input);
        // if($response == Password::RESET_LINK_SENT) {
        //     $message = "Mail Send Successfully";
        // } else {
        //     $message = "Email could not be sent to this email address";
        // }

        $response = ['data' => '', 'message' => 'Password reset link sent successfully'];
        return response($response, 200);
    }

    
}
