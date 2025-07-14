<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\IndexRequest;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends Controller
{
    public function user(Request $request)
    {   
        $user = Auth::user();

        if (empty($user))    
        {
            $user = ["email" => "guest"];
        }
            
        return view('profile', ["user" => $user]);
    }        
        
    public function login(Request $request)
    {        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]); 

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return ["status" => "ok"];
        } else {
            $er = ["status" => "error", "error" => "Ошибка"];
            return $er;
        } 
    }  
    public function registration(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'gender' => 'required|exists:genders,id',
            'email' => 'unique:users,email|email:rfc,dns',
            'password' => 'required'
        ]);
 
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $er = ["status" => "error", "error" => $error];
            return $er;
        } else {
            $user = [
                "email" => $request->email,
                "name" => $request->email,
                "gender" => $request->gender,
                "password" => $request->password
            ];
            $user = User::create($user);

            Auth::loginUsingId($user->id);
            return ["status" => "ok", "user" => $user];
        } 
    }

    public function profile(Request $request)
    {        
        $user = Auth::user();
        
        if (!empty($user))
            return ["status" => "ok", "user" => $user];
        else 
            return ["status" => "error"];

    }
    public function logout(Request $request)
    {            
        Auth::logout();        
        return ["status" => "ok"];
    }
}
