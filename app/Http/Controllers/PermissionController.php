<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesApiResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use HandlesApiResponse;

    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->safeCall(function () {
            $permissions = Permission::all(); // Retrieve all permissions
            return $this->successResponse(
                'Permissions fetched successfully',
                ['permissions' => $permissions]
            );
        });
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->safeCall(function () use ($request) {
            // Validate incoming request
            $request->validate([
                'name' => 'required|unique:permissions,name',
            ]);

            // Create permission
            $permission = Permission::create(['name' => $request->name]);

            return $this->successResponse(
                'Permission created successfully',
                ['permission' => $permission],
                201
            );
        });
    }

    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->safeCall(function () use ($id) {
            $permission = Permission::findOrFail($id); // Find the permission by ID
            return $this->successResponse(
                'Permission fetched successfully',
                ['permission' => $permission]
            );
        });
    }

    /**
     * Update the specified permission in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return $this->safeCall(function () use ($request, $id) {
            // Validate incoming request
            $request->validate([
                'name' => 'required|unique:permissions,name,' . $id,
            ]);

            // Find the permission by ID
            $permission = Permission::findOrFail($id);
            $permission->name = $request->name; // Update the permission name
            $permission->save(); // Save changes

            return $this->successResponse(
                'Permission updated successfully',
                ['permission' => $permission]
            );
        });
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->safeCall(function () use ($id) {
            // Find the permission by ID
            $permission = Permission::findOrFail($id);
            $permission->delete(); // Delete the permission

            return $this->successResponse(
                'Permission deleted successfully',
                ['permission' => $permission]
            );
        });
    }
}
