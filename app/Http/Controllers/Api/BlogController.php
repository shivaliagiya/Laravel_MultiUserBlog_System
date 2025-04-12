<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Retrieve all approved blog posts with pagination.
     */
    public function index(Request $request)
    {
        $posts = BlogPost::where('status', 'approved')->paginate(5);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Retrieve a single blog post by ID.
     */
    public function show($id)
    {
        $post = BlogPost::with(['category', 'user', 'comments.user'])->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

}
