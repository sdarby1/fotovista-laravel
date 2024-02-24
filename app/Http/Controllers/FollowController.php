<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Benutzer folgen
    public function follow($userId)
    {
        $user = Auth::user();
        $userToFollow = User::find($userId);

        if (!$userToFollow) {
            return response()->json(['message' => 'Benutzer nicht gefunden'], 404);
        }

        if ($user->id === $userToFollow->id) {
            return response()->json(['message' => 'Du kannst dir nicht selbst folgen'], 400);
        }

        $user->following()->attach($userId);

        return response()->json(['message' => 'Du folgst jetzt diesem Benutzer']);
    }

    // Benutzer entfolgen
    public function unfollow($userId)
    {
        $user = Auth::user();
        $user->following()->detach($userId);

        return response()->json(['message' => 'Du folgst diesem Benutzer nicht mehr']);
    }

    // Follower eines Benutzers anzeigen
    public function followers($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'Benutzer nicht gefunden'], 404);
        }

        $followers = $user->followers;

        return response()->json(['followers' => $followers]);
    }

    // Benutzern, denen ein Benutzer folgt, anzeigen
    public function following()
    {
        $user = Auth::user(); // Holt den aktuell authentifizierten Benutzer

        if (!$user) {
            return response()->json(['message' => 'Nicht authentifiziert'], 401);
        }

        $following = $user->following; // Benutzt die vorhandene Beziehung

        return response()->json(['following' => $following]);
    }


    // In FollowController

    public function isFollowing($userId)
    {
        $authUser = Auth::user(); // Der authentifizierte Benutzer

        if (!$authUser) {
            return response()->json(['message' => 'Nicht authentifiziert'], 401);
        }

        $userToCheck = User::find($userId);

        if (!$userToCheck) {
            return response()->json(['message' => 'Benutzer nicht gefunden'], 404);
        }

        $isFollowing = $authUser->following()->where('users.id', $userId)->exists();

        return response()->json(['isFollowing' => $isFollowing]);
    }

}

