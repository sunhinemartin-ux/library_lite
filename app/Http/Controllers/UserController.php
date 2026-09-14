<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin-dashboard', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,librarian,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'New user created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,librarian,student',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}