<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
        ]);

        if ($request->hasFile('profile_image')) {
            $imageName = time().'.'.$request->profile_image->extension();
            $request->profile_image->move(public_path('profile_images'), $imageName);
            $user->profile_image = 'profile_images/' . $imageName;
        }

        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->save();

        return response()->json(['message' => 'Profil erfolgreich aktualisiert', 'user' => $user]);
    }
}

