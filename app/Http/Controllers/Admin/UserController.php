<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The only way an admin account is created or removed from the web UI.
 *
 * There is still no public registration and no emailed password reset — see
 * App\Http\Controllers\Admin\SessionController. An account created here signs
 * in exactly the same way one created with `php artisan corex:admin` does.
 */
class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::orderBy('name')->get(['id', 'name', 'email', 'created_at']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index')->with('admin_status', 'User created.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('admin_status', 'User saved.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if (User::count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'The last remaining account cannot be deleted — there would be nobody left who could sign in.',
            ]);
        }

        if ($request->user()->is($user)) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account while signed in as it.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('admin_status', 'User deleted.');
    }
}
