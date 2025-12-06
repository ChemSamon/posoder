@extends('layouts.app2')

@section('title', 'Admin Login')

@section('content')
<div class="form-box">
  <h2 class="text-center mb-4">Admin Login</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login1') }}">
    @csrf
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required autofocus>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>
@endsection
