@extends('layouts.app2')

@section('title', 'Login')

@section('content')
<div class="form-box">
  <h2 class="text-center mb-4">Login</h2>

  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
      <label for="loginEmail" class="form-label">Email address</label>
      <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter email" required>
    </div>
    <div class="mb-3">
      <label for="loginPassword" class="form-label">Password</label>
      <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>

  <!-- <div class="register-link">
      Don't have an account? <a href="{{ url('/register') }}">Register here</a>
  </div> -->
</div>
@endsection
