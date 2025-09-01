<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.user-list', compact('users'));
    }

  public function create()
    {
        $roles = Role::all();
        return view('users.new-user', compact('roles'));
    }

    public function store(Request $request)
    {


        try {
            DB::beginTransaction();

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role_id' => 'required|exists:roles,RoleID', // Vérifie la clé primaire
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Création de l'utilisateur
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'RoleID' => $request->role_id,
            ]);

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit-user', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8|confirmed',
                'role_id' => 'required|exists:roles,RoleID', // Vérifie la clé primaire
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'RoleID' => $request->role_id,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Prevent deletion of superadmin
            if ($user->hasRole('superadmin')) {
                throw new \Exception('Cannot delete superadmin user.');
            }

            $user->delete();

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
