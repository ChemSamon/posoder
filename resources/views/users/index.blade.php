@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="text-primary fw-bold">
            <i class="bi bi-people-fill me-2"></i>{{ __('messages.User List') }}
        </h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>{{ __('messages.Create User') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg">
        {{-- <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-muted">{{ count($users) }} Users Registered</h5>
        </div> --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 35%;">{{ __('messages.Name') }}</th>
                            <th style="width: 35%;">{{ __('messages.Email') }}</th>
                            <th style="width: 25%;">{{ __('messages.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td><span class="text-muted">{{ $user->email }}</span></td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-2 d-inline-flex align-items-center" title="{{ __('messages.Edit') }}">
                                    <i class="bi bi-pencil-square me-1"></i>{{ __('messages.Edit') }}
                                </a>
                                
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete the user: {{ $user->name }}? This action cannot be undone.')" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" title="{{ __('messages.Delete') }}">
                                        <i class="bi bi-trash me-1"></i>{{ __('messages.Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i>No users found. Click "Create User" to add an account.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection