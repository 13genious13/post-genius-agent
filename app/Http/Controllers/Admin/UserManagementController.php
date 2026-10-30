<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));
                $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->withCount('businesses')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        User::create([...$data, 'is_active' => true]);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $user, $data) {
            $locked = User::query()->lockForUpdate()->findOrFail($user->id);
            if ($locked->id === $request->user()->id &&
                ($data['role'] !== 'admin' || !$data['is_active'])) {
                abort(422, 'You cannot remove your own administrator access.');
            }
            if ($locked->role === 'admin' &&
                ($data['role'] !== 'admin' || !$data['is_active']) &&
                User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
                abort(422, 'At least one active administrator is required.');
            }
            $locked->update($data);
        });

        return back()->with('success', 'User updated successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => ['required', 'confirmed', Password::defaults()]]);
        $user->forceFill(['password' => Hash::make($request->input('password')),
            'remember_token' => Str::random(60)])->save();
        return back()->with('success', 'Password changed. Ask the user to sign in again.');
    }
}
