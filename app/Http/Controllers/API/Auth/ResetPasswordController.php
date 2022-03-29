<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    protected function sendResetResponse(Request $request)
    {
        $input = $request->only('email', 'token', 'password', 'password_confirmation');
        $validator = Validator::make($input,[
            'token' => 'required',
            'email' => 'required|mail',
            'password' => 'required|confirmed|min:8'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $response = Password::reset($input, function($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
            event(new PasswordReset($user));
        });

        if($response == Password::PASSWORD_RESET) {
            $message = "Password reset successfully";
        } else {
            $message = "Email could not be sent out";
        }
        
        $response = ['data' => '', 'message' => $message];
        return response()->json($response);
    }

    /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) { 
        return view('auth.forgetPasswordLink', ['token' => $token]);
     }

    public function submitResetPassword(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email|exists:users',
        //     'password' => 'required|string|min:6|confirmed',
        //     'password_confirmation' => 'required'
        // ]);

        $updatePassword = DB::table('password_resets')
                ->where(['email' => $request->email, 'token' => $request->token])
                ->first();
        
        if(!$updatePassword) {
            return response()->json('Error reseting password');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return response()->json('Password changed successfully');
    }
}
