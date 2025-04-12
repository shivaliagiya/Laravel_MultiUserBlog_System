@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="category-header">
            <h2>Categories</h2>
            <div class="button-group">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">Add New Category</a>
                <a href="{{ route('blog.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="category-list">
            @foreach ($categories as $category)
                <div class="category-item">
                    <span>{{ $category->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection
