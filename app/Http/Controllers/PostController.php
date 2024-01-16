<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Importieren Sie Ihr Post-Modell
use Illuminate\Support\Facades\Auth; // Importieren Sie die Auth-Fassade

class PostController extends Controller
{
    public function create(Request $request)
    {
        // Validierung der Eingaben
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
            'camera' => 'nullable|string',
            'lens' => 'nullable|string',
            'filter' => 'nullable|string',
            'tripod' => 'nullable|string',
        ]);

        // Überprüfung, ob der Benutzer authentifiziert ist
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorisiert'], 401);
        }

        // Extrahieren des authentifizierten Benutzers
        $user = Auth::user();

        try {
            // Bildverarbeitung
            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);

            // Post erstellen
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
            // Fehlerbehandlung
            return response()->json(['message' => 'Fehler beim Erstellen des Posts', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $post = Post::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post nicht gefunden'], 404);
        }

        return response()->json(['post' => $post]);

    }
}
