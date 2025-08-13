<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userServices
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $query = User::query()
            ->latest();

        $query->when($request->input('search'), function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");;
            });
        });

        $users = $query->paginate(10)->withQueryString();

        return Inertia::render('User/UserList', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $roles = UserRoleEnum::toArray();

        return Inertia::render('User/UserForm', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->userServices->store($request);

        return redirect()->route('user.index')->with('success', 'Akun '. $request->name .' Berhasil di Tambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('user.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = UserRoleEnum::toArray();

        return Inertia::render('User/UserForm', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $this->userServices->update($request, $user);

        return redirect()->route('user.index')->with('success', 'Akun '. $request->name .' Berhasil di Ubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->id == auth()->user()->id) return redirect()->route('user.index')->with('error', 'Akun '. $user->name .' Gagal di Hapus');

        $this->userServices->delete($user);

        return redirect()->route('user.index')->with('success', 'Akun '. $user->name .' Berhasil di Hapus');
    }
}
