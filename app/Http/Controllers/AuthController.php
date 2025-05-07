<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        return redirect()->intended('/home');

    }

    public function logout(){
        auth()->logout();
        return redirect()->intended('/');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
