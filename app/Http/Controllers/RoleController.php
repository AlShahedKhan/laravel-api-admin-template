<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesApiResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use HandlesApiResponse;

    // Display a listing of roles
    public function index()
    {
        return $this->safeCall(function () {
            $roles = Role::all();
            return $this->successResponse(
                'Roles fetched successfully',
                ['roles' => $roles]
            );
        });
    }

    // Show the form for creating a new role
    public function create()
    {
        // Normally would return a view, but for API, let's just return a message
        return $this->successResponse('Create a new role');
    }

    // Store a newly created role in storage
    public function store(Request $request)
    {
        return $this->safeCall(function () use ($request) {
            $request->validate([
                'name' => 'required|unique:roles,name',
            ]);

            $role = Role::create(['name' => $request->name]);

            return $this->successResponse(
                'Role created successfully',
                ['role' => $role],
                201
            );
        });
    }

    // Display the specified role
    public function show($id)
    {
        return $this->safeCall(function () use ($id) {
            $role = Role::findOrFail($id);
            return $this->successResponse(
                'Role fetched successfully',
                ['role' => $role]
            );
        });
    }

    // Show the form for editing the specified role
    public function edit($id)
    {
        return $this->safeCall(function () use ($id) {
            $role = Role::findOrFail($id);
            return $this->successResponse(
                'Role details fetched successfully',
                ['role' => $role]);
        });
    }

    // Update the specified role in storage
    public function update(Request $request, $id)
    {
        return $this->safeCall(function () use ($request, $id) {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id,
            ]);

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            return $this->successResponse(
                'Role updated successfully',
                ['role' => $role]
            );
        });
    }

    // Remove the specified role from storage
    public function destroy($id)
    {
        return $this->safeCall(function () use ($id) {
            $role = Role::findOrFail($id);
            $role->delete();

            return $this->successResponse(
            'Role deleted successfully',
            ['role' => $role]
        );
        });
    }
}
