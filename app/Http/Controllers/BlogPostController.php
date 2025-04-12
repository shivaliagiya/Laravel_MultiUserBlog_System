<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    // Show all approved posts (Public)
    public function index(Request $request)
    {
        $query = BlogPost::where('status', 'approved');

        // Search functionality
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('content', 'LIKE', '%' . $request->search . '%');
            });
        }

        $posts = $query->paginate(5);

        return view('blog.index', compact('posts'));
    }


    // Show create form (Only for logged-in users)
    public function create()
    {
        $categories = Category::all();
        return view('blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        BlogPost::create([
            'user_id' => Auth::id(),
            'category_id' => $validatedData['category_id'],
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'status' => 'pending', // New posts need admin approval
        ]);

        return redirect()->route('blog.index')->with('success', 'Post submitted for approval.');
    }

    public function show($id)
    {
        $post = BlogPost::with('comments.user')->findOrFail($id);
        return view('blog.show', compact('post'));
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('blog.index')->with('error', 'Unauthorized Access!');
        }

        $categories = Category::all();
        return view('blog.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('blog.index')->with('error', 'Unauthorized Access!');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post->update([
            'category_id' => $validatedData['category_id'],
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ]);

        return redirect()->route('blog.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Access Denied');
        }

        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('blog.index')->with('success', 'Post deleted successfully.');
    }

    // Approve a blog post (Only Admin)
    public function approve($id)
    {
        $post = BlogPost::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Access Denied');
        }

        $post->status = 'approved';
        $post->save();

        return redirect()->back()->with('success', 'Post approved successfully.');
    }

}
