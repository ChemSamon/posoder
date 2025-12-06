{{-- resources/views/register.blade.php --}}
@extends('layouts.app2')

@section('title', 'Register')

@section('content')
<div class="form-box">
  <h2 class="text-center mb-4">Register</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
      <label for="registerName" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="registerName" name="name" placeholder="Enter full name" required>
    </div>
    <div class="mb-3">
      <label for="registerEmail" class="form-label">Email address</label>
      <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter email" required>
    </div>
    <div class="mb-3">
      <label for="registerPassword" class="form-label">Password</label>
      <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create password" required>
    </div>
    <div class="mb-3">
      <label for="registerPasswordConfirm" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="registerPasswordConfirm" name="password_confirmation" placeholder="Confirm password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
  </form>

  <div class="register-link">
    Already have an account? <a href="{{ url('/login') }}">Login here</a>
  </div>
</div>
@endsection
