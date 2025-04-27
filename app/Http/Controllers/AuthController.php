<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        return view('login');
    }
    public function login(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            Log::error('Login validation failed', $validator->errors()->toArray());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        if(!auth()->attempt($request->only('email', 'password'))){
            return redirect()->back()->withErrors(['password'=>'Invalid email or password', 'email' => 'Invalid email or password']);
        }

        return redirect()->intended('/');

    }

    public function logout(){
        auth()->logout();
        return redirect()->intended('/');
    }
}
