<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('users', 'public');
        }
        $user = $this->userService->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully!',
            'data' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('users', 'public');
        }
        $user = $this->userService->update($user, $validated);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully!',
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully!',
        ]);
    }

    public function show($id)
    {
        $user = $this->userService->getUser($id);
        return response()->json([
            'status' => true,
            'message' => 'User data found successfully!',
            'data' => $user
        ]);
    }

    public function getUsers()
    {
        $data = $this->userService->where('role', '!=', 'admin')->with('userDetail')->get();

        return response()->json([
            'status' => true,
            'message' => 'User data found!',
            'data' => $data
        ]);
    }

    public function removeProfileImage($id)
    {
        $removed = $this->userService->removeProfileImage($id);

        if(!$removed){
            return response()->json([
                'status' => false,
                'message' => 'User profile image not removed!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile image removed successfully!',
        ]);
    }
}
