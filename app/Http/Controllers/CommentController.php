<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class CommentController extends Controller
{

    public function storeComment(Request $request, $postId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Nicht authentifiziert'], 401);
        }
    
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);
    
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'body' => $validatedData['body'],
        ]);
    
        return response()->json(status: 204);
    }
    
    public function storeReply(Request $request, $commentId)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);

        $reply = Reply::create([
            'user_id' => Auth::id(),
            'comment_id' => $commentId,
            'body' => $validatedData['body'],
        ]);

        return response()->json(status: 204);
    }

    public function getPostComments($postId)
    {
        $comments = Comment::with(['user', 'replies.user'])->where('post_id', $postId)->get();
        return response()->json(['comments' => $comments]);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Kommentar nicht gefunden'], 404);
        }

        // Überprüfe, ob der angemeldete User Admin ist
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Nicht autorisiert'], 403);
        }

        try {
            $comment->delete();
            return response()->json(['message' => 'Kommentar erfolgreich gelöscht']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Fehler beim Löschen des Kommentars', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteReply($replyId)
    {
        $reply = Reply::find($replyId);

        if (!$reply) {
            return response()->json(['message' => 'Antwort nicht gefunden'], 404);
        }

        // Überprüfe, ob der angemeldete User Admin ist
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Nicht autorisiert'], 403);
        }

        try {
            $reply->delete();
            return response()->json(['message' => 'Antwort erfolgreich gelöscht']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Fehler beim Löschen der Antwort', 'error' => $e->getMessage()], 500);
        }
    }

}
