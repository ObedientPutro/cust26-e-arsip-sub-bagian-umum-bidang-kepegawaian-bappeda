<?php

namespace App\Services;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function store(StoreUserRequest $request): void
    {
        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => strtolower($request->email),
            'username' => strtolower($request->username),
            'password' => Hash::make($request->password),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): void
    {
        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'email' => strtolower($request->email),
            'username' => strtolower($request->username),
        ]);

        if($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
