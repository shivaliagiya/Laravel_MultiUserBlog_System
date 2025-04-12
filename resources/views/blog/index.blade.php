@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Blog Posts</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Form -->
        <form action="{{ route('blog.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search blog posts..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add Category</a>
        <a href="{{ route('blog.create') }}" class="btn btn-primary mb-3">Create New Post</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td><span class="badge bg-{{ $post->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($post->status) }}</span></td>
                    <td>{{ $post->user->name }}</td>
                    <td>
                        <a href="{{ route('blog.show', $post->id) }}" class="btn btn-sm btn-info">View</a>
                        @if(Auth::check() && (Auth::user()->id == $post->user_id || Auth::user()->isAdmin()))
                            <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('blog.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endif
                        @if(Auth::check() && Auth::user()->isAdmin() && $post->status == 'pending')
                            <form action="{{ route('blog.approve', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination Links with Bootstrap Styling -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
