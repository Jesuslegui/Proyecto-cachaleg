<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate(['role' => 'required|in:admin,user']);
        if ($user->is(Auth::user())) {
            return back()->withErrors(['role' => 'No puedes cambiar tu propio rol.']);
        }

        $user->update($data);
        return back()->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->withErrors(['user' => 'No puedes desactivar tu propia cuenta.']);
        }

        $user->delete();
        return back()->with('success', 'Usuario desactivado.');
    }
}