<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|min:1',
            'blog_post_id' => 'required|exists:blog_posts,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'blog_post_id' => $validatedData['blog_post_id'],
            'content' => $validatedData['content'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'user' => Auth::user()->name,
                'content' => $comment->content
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

}
