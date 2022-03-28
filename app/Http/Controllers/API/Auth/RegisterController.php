<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RegisterController extends BaseController
{
    
    /**
     * Register Controller
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('xadmapi')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User Registered Successfully!');
    }

    /**
     * Login API
     */
    public function login(Request $request) 
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token']  = $user->createToken('xadmapi')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User Logged In Successfully');
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized']);
        }
    }
}
