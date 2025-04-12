@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $post->title }}</h2>
        <p><strong>Category:</strong> {{ $post->category->name }}</p>
        <p><strong>Author:</strong> {{ $post->user->name }}</p>
        <p><strong>Status:</strong> <span class="badge bg-{{ $post->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($post->status) }}</span></p>
        <hr>
        <p>{!! nl2br(e($post->content)) !!}</p>

        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Back to List</a>

        @if(Auth::id() == $post->user_id || Auth::user()->role == 'admin')
            <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('blog.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        @endif

        <hr>

        <!-- Display Comments -->
        <h4>Comments</h4>
        <div id="comments-section">
            @foreach($post->comments as $comment)
                <div class="card my-2">
                    <div class="card-body">
                        <p><strong>{{ $comment->user->name }}</strong> said:</p>
                        <p>{{ $comment->content }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        @auth
            <!-- Comment Form -->
            <form id="commentForm">
                @csrf
                <input type="hidden" name="blog_post_id" value="{{ $post->id }}">
                <div class="form-group">
                    <label for="content">Write a comment:</label>
                    <textarea name="content" id="content" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
            </form>
        @else
            <p class="text-muted">You must be logged in to post a comment.</p>
        @endauth

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#commentForm').submit(function (e) {
                e.preventDefault(); // Prevent normal form submission

                $.ajax({
                    url: "{{ route('comments.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#comments-section').prepend(`
                            <div class="card my-2">
                                <div class="card-body">
                                    <p><strong>${response.user}</strong> said:</p>
                                    <p>${response.content}</p>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        `);
                            $('#content').val(''); // Clear the textarea
                        } else {
                            alert('Failed to post comment');
                        }
                    },
                    error: function (xhr) {
                        alert('Error posting comment: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>

@endsection
