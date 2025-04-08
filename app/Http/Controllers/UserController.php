<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RoleService;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RoleService;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            if (!session('token')) {
                return redirect()->route('login')->withErrors('Anda perlu login terlebih dahulu.');
            }

            $users = $this->userService->getAllUsers()->json('data') ?? [];
            $roles = $this->roleService->getAllRoles()->json('data') ?? [];

            return view('frontend.user.index', compact('users', 'roles'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->userService->createUser($request->only([
                'name',
                'email',
                'password',
                'password_confirmation',
                'roles'
            ]));

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
            }

            return back()->withErrors(['message' => 'Gagal menyimpan user.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userService->getUser($id)->json('data');
            $roles = $this->roleService->getAllRoles()->json('data');

            return view('frontend.user.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['name', 'email', 'roles']);

            if ($request->filled('password')) {
                $data['password'] = $request->input('password');
                $data['password_confirmation'] = $request->input('password_confirmation');
            }

            $response = $this->userService->updateUser($id, $data);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
            }

            return back()->withErrors(['message' => 'Gagal memperbarui user.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->userService->deleteUser($id);

            if ($response->successful()) {
                return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
            }

            return back()->withErrors(['message' => 'Gagal menghapus user.']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
