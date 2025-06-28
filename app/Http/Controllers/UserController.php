<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource and the form to create a new one.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin-only');

        $users = User::orderBy('username')->get();
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'User Management']];

        return view('users.index', compact('users', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin-only');

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,cashier'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imageName = 'default_avatar.png';
        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('public/avatars', $imageName);
        }

        User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'profile_picture' => $imageName,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * THE FIX: This 'edit' method was missing from your file.
     * It shows the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('admin-only');
        
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')], 
            ['name' => 'User Management', 'url' => route('users.index')],
            ['name' => 'Edit User']
        ];

        return view('users.edit', compact('user', 'breadcrumbs'));
    }

    /**
     * THE FIX: This 'update' method was also missing.
     * It saves the changes for the specified user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('admin-only');

        // We validate the username to be unique, but ignore the current user's own username.
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'role' => ['required', 'string', 'in:admin,cashier'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $input = $request->except(['_token', '_method', 'profile_picture']);

        if ($request->hasFile('profile_picture')) {
            // Delete the old avatar if it's not the default one
            if ($user->profile_picture && $user->profile_picture != 'default_avatar.png') {
                Storage::delete('public/avatars/' . $user->profile_picture);
            }
            
            $imageName = time() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('public/avatars', $imageName);
            $input['profile_picture'] = $imageName;
        }

        $user->update($input);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
}
