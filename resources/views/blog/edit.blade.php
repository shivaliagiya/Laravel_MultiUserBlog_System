@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Blog Post</h2>

        <form action="{{ route('blog.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $post->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required>{{ $post->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update Post</button>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
