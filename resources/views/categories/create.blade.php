@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="category-form">
            <h2>Add New Category</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Category Name" required class="input-field">
                <button type="submit" class="btn btn-primary">Add</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection
