@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Category</h2>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
