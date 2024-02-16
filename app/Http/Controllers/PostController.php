<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use Illuminate\Support\Facades\Auth; 


class PostController extends Controller
{

    // Posts erstellen

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
            'camera' => 'nullable|string',
            'lens' => 'nullable|string',
            'filter' => 'nullable|string',
            'tripod' => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorisiert'], 401);
        }

        $user = Auth::user();

        try {
            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);

            $post = Post::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image_path' => 'images/' . $imageName,
                'user_id' => $user->id,
                'camera' => $validatedData['camera'],
                'lens' => $validatedData['lens'],
                'filter' => $validatedData['filter'],
                'tripod' => $validatedData['tripod'],
            ]);

            return response()->json(['message' => 'Post erfolgreich erstellt', 'post' => $post], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Fehler beim Erstellen des Posts', 'error' => $e->getMessage()], 500);
        }
    }



    // Posts bearbeiten

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'camera' => 'nullable|string',
            'lens' => 'nullable|string',
            'filter' => 'nullable|string',
            'tripod' => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorisiert'], 401);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post nicht gefunden'], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unbefugter Zugriff'], 403);
        }

        try {
            $post->update($validatedData);
            return response()->json(['message' => 'Post erfolgreich aktualisiert', 'post' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Fehler beim Aktualisieren des Posts', 'error' => $e->getMessage()], 500);
        }
    }



    // Einzelnen Post anzeigen

    public function show($id)
    {
        $post = Post::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post nicht gefunden'], 404);
        }

        return response()->json(['post' => $post]);

    }



    // Posts des eingeloggten Users anzeigen

    public function userPosts($userId)
    {
        $posts = Post::where('user_id', $userId)->get();

        return response()->json(['posts' => $posts]);
    }




    // Alle Posts anzeigen

    public function allPosts()
    {
        $posts = Post::all();

        return response()->json(['posts' => $posts]);
    }


    // Post löschen

    /**
     * Post löschen
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function deletePost($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorisiert'], 401);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post nicht gefunden'], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unbefugter Zugriff'], 403);
        }

        try {
            $post->delete();
            return response()->json(['message' => 'Post erfolgreich gelöscht']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Fehler beim Löschen des Posts', 'error' => $e->getMessage()], 500);
        }
    }


}

