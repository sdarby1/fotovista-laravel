<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Suche nach Benutzern, deren Benutzernamen den Suchbegriff enthalten
        $users = User::where('username', 'LIKE', "%$query%")->get();

        // Suche nach Posts, deren Titel den Suchbegriff enthalten
        $posts = Post::where('title', 'LIKE', "%$query%")->get();

        return response()->json([
            'users' => $users,
            'posts' => $posts,
        ]);
    }
}
