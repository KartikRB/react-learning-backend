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

    public function show(User $user)
    {
        $user->details = $user->userDetail;
        return response()->json([
            'status' => true,
            'message' => 'User data found successfully!',
            'data' => $user
        ]);
    }

    public function getUsers()
    {
        $data = $this->userService->where('role', '!=', 'admin')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data found!',
            'data' => $data
        ]);
    }
}
