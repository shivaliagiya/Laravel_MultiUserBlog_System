@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Blog Post</h2>

        <form action="{{ route('blog.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>

            <!-- Status Column (Hidden) -->
            <input type="hidden" name="status" value="pending">

            <button type="submit" class="btn btn-success">Submit for Approval</button>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
