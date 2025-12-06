@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<h2>Create New User</h2>

<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
