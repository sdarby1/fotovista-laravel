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
        $comments = Comment::with('user')->where('post_id', $postId)->get();
        return response()->json(['comments' => $comments]);
    }

}
