<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Traits\BaseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use BaseTrait;

    // Index
    public function login(){
        return view('web.login');
    }

    // Register
    public function register(){
        return view('web.register');
    }

    // Login Check
    public function loginCheck(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendValidationError($validator->errors());
        }

        $params = $request->only('email','password');
        $login = Auth::guard('web')->attempt($params);

        if($login){
            Auth::guard('web')->user()->setToken();
            return $this->sendSuccess("Logged in successfully !");
        }else{
            return $this->sendError("Failed to sign in !");
        }
    }

    // Register User
    public function registerUser(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return $this->sendValidationError($validator->errors());
        }

        $params = $request->only('email');
        $params['password'] = Hash::make($request->get('password'));
        $insert = new User();
        $insert->fill($params)->save();

        if($insert){
            return $this->sendSuccess("Account registered !");
        }else{
            return $this->sendError("Failed to signup !");
        }
    }
}
