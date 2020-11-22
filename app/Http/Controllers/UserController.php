<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(Request $request)
    {
        
        $this->validate($request,[
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',


        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $hashpassword = Hash::make($password);

        $user = User::create([
            'email' => $email,
            'password' => $hashpassword
        ]);


            return response()->json(['message' => 'Success'], 201);
        
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|',
            'password' => 'required|min:6',
        ]);

        
        $email = $request->input('email');
        $password = $request->input('password');

        $user= User::where('email',$email)->first();
        if(!$user){
            return response()->json(['message' => 'Login Failed'], 401);
        }

        $isvalidPassword = Hash::check($password, $user->password);
        if(!$isvalidPassword) {
            return response()->json(['message' => 'Login Failed'], 401);
        }
        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);

        return response()->json($user);
    }
}