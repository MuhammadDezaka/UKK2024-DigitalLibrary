<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\AdminUserRepository;
use App\Http\Requests\AdminUser\StoreRequest;
use App\Http\Requests\AdminUser\DeleteRequest;
use App\Http\Requests\AdminUser\UpdateRequest;
use App\Http\Requests\AdminUser\ChangePasswordRequest;

class AdminUserController extends Controller
{
    protected $adminUserRepository;

    /**
     * Construct
     */
    public function __construct(
        AdminUserRepository $adminUserRepository)
    {
        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * Index
     */
    public function index()
    {
        return view('admin.dashboard.user.admin-user.index');
    }

    /**
     * Tables
     */
    public function tables(Request $request)
    {
        $requestData['keyword'] = $request->keyword;

        return $this->adminUserRepository->getCmsTables($requestData);
    }

    /**
     * Store
     */
    public function store(StoreRequest $request)
    {
        $request = $request->validated();

        // Get Detail Role
        $role = \Facades\App\Repositories\AdminRoleRepository::find($request['role_id']);

        // Add to Request
        $request['role'] = $role['name'];
        $request['password'] = Hash::make($request['password']);

        $user = $this->adminUserRepository->create($request);


        return ['status' => 'success'];
    }

    /**
     * Update
     */
    public function update(UpdateRequest $request)
    {
        $request = $request->validated();
        // Get Detail Role
        $role = \Facades\App\Repositories\AdminRoleRepository::find($request['role_id']);
        // Check Role User
        $user = User::find($request['id']);

        if ( ! $user->hasRole($role['name'])) {
            $currentRole = $user->roles->first();
            if ($currentRole) {
                $user->removeRole($currentRole->name);
            }

            $user->assignRole($role['name']);
        }

        unset($request['role_id']);

        $this->adminUserRepository->update($request['id'], $request);

        return ['status' => 'success'];
    }

    /**
     * Delete
     */
    public function delete(DeleteRequest $request)
    {
        $request = $request->validated();
        // Find User
        $user = User::find($request['id']);
        // Delete Roles
        if ($user->roles) {
            foreach ($user->roles as $role) {
                $user->removeRole($role->name);
            }
        }
        // Delete User
        $user->delete();

        return ['status' => 'success'];
    }


    /**
     * Change Password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $request = $request->validated();
        $request['password'] = Hash::make($request['password']);
        $this->adminUserRepository->update($request['id'], $request);
        return ['status' => 'success'];
    }

}
