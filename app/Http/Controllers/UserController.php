<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\HandlesApiResponse; // Assuming you are using the same response trait

class UserController extends Controller
{
    use HandlesApiResponse;

    // Display a listing of users
    public function index()
    {
        return $this->safeCall(function () {
            $users = User::all();
            return $this->successResponse(
                'Users fetched successfully',
                ['users' => $users]
            );
        });
    }

    // Store a newly created user
    public function store(Request $request)
    {
        return $this->safeCall(function () use ($request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return $this->successResponse(
                'User created successfully',
                ['user' => $user],
                201
            );
        });
    }

    // Display the specified user
    public function show($id)
    {
        return $this->safeCall(function () use ($id) {
            $user = User::findOrFail($id);
            return $this->successResponse(
                'User fetched successfully',
                ['user' => $user]
            );
        });
    }

    // Update the specified user
    public function update(Request $request, $id)
    {
        return $this->safeCall(function () use ($request, $id) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            return $this->successResponse(
                'User updated successfully',
                ['user' => $user]
            );
        });
    }

    // Remove the specified user
    public function destroy($id)
    {
        return $this->safeCall(function () use ($id) {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->successResponse(
                'User deleted successfully',
                ['user' => $user]
            );
        });
    }
}
