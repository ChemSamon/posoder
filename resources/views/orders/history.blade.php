@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid py-4">
    <h2 class="mb-4 text-primary fw-bold border-bottom pb-2">
        <i class="bi bi-clock-history me-2"></i>{{ __('messages.Order History') }}
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Card - Already Responsive --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            
            <form action="{{ route('orders.history') }}" method="GET" class="row g-2 align-items-end mb-3">
                <div class="col-md-5 col-lg-3">
                    <label for="startDate" class="form-label text-muted small mb-0">{{ __('messages.Start Date') }}</label>
                    <input type="text" name="start_date" id="startDate" class="form-control form-control-sm" placeholder="{{ __('messages.Start Date') }}" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-5 col-lg-3">
                    <label for="endDate" class="form-label text-muted small mb-0">{{ __('messages.End Date') }}</label>
                    <input type="text" name="end_date" id="endDate" class="form-control form-control-sm" placeholder="{{ __('messages.End Date') }}" value="{{ request('end_date') }}">
                </div>
                
                <div class="col-md-2 col-lg-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-funnel me-1"></i>{{ __('messages.Filter') }}
                    </button>
                </div>
            </form>

            <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                    <i class="bi bi-list-ul me-1"></i>{{ __('messages.All Orders') }}
                </a>
                <a href="{{ route('orders.daily') }}" class="btn btn-outline-info btn-sm d-flex align-items-center">
                    <i class="bi bi-calendar-day me-1"></i>{{ __('messages.Today') }}
                </a>
                <a href="{{ route('orders.monthly') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center">
                    <i class="bi bi-calendar-month me-1"></i>{{ __('messages.This Month') }}
                </a>
                <form action="{{ route('orders.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all order history?')" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                        <i class="bi bi-trash me-1"></i>{{ __('messages.Clear History') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-muted">
            {{ $orders->total() }} 
            {{ $orders->total() == 1 ? 'Order' : __('messages.Orders') }} 
            {{ __('messages.Found') }}
        </h5>
    </div>

        <div class="card-body p-0">
            {{-- .table-responsive allows horizontal scrolling IF the table is still too wide --}}
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            {{-- # Column: Hide on screens smaller than small (xs) --}}
                            <th class="d-none d-sm-table-cell">#</th> 
                            <th>{{ __('messages.Receipt Number') }}</th>
                            <th>{{ __('messages.Total') }}</th>
                            
                            {{-- Discount/Total After Discount: Hide on screens smaller than medium (mobile) --}}
                            <th class="d-none d-md-table-cell">{{ __('messages.Discount') }} (%)</th>
                            <th class="d-none d-md-table-cell">{{ __('messages.Total After Discount') }}</th>
                            
                            {{-- Created At: Hide on screens smaller than medium (mobile) --}}
                            <th class="d-none d-md-table-cell">{{ __('messages.Created At') }}</th>
                            
                            {{-- Items Column: Reduced min-width to 200px for better fit --}}
                            <th style="min-width: 200px;">{{ __('messages.Items') }}</th>
                            
                            {{-- Note Column: Hide on screens smaller than large (tablet) --}}
                            <th class="d-none d-lg-table-cell" style="min-width: 150px;">{{ __('messages.Note') }}</th>
                            
                            {{-- Action Column: Reduced min-width and will shorten text --}}
                            <th style="min-width: 100px;">{{ __('messages.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr class="align-middle">
                            {{-- Data Cells: Must match the visibility of the header cells --}}
                            <td class="small d-none d-sm-table-cell">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-primary small">{{ $order->receipt_number }}</td>
                            <td class="text-danger small">${{ number_format($order->total, 2) }}</td>
                            
                            <td class="small d-none d-md-table-cell">{{ $order->discount }}%</td>
                            <td class="fw-bold text-success small d-none d-md-table-cell">${{ number_format($order->total_after_discount, 2) }}</td>
                            
                            <td class="small d-none d-md-table-cell">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 small" style="padding: 0;">
                                    @foreach($order->items as $item)
                                        <li class="p-1 border-bottom d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="fw-semibold">{{ $item->product_name }}</span> 
                                                <span class="badge bg-secondary ms-1">x{{ $item->quantity }}</span>
                                                
                                                @if(!empty($item->note))
                                                    <div class="text-muted fst-italic" style="font-size: 0.75rem; line-height: 1;">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        {{ __('messages.Chlil') }}:{{ $item->note }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="fw-bold text-muted">${{ number_format($item->line_total, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if(!empty($order->note))
                                    <span class="badge bg-secondary-subtle text-dark p-2">{{ $order->note }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.print', $order->id) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                    <i class="bi bi-printer me-1"></i> 
                                    {{-- Use full text on medium+ screens, and shorten on small screens --}}
                                    <span class="d-none d-sm-inline">{{ __('messages.Print Receipt') }}</span>
                                    <span class="d-sm-none">Print</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox me-2"></i>No orders found matching the criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if ($orders->hasPages())
        <div class="card-footer bg-light d-flex justify-content-center border-top py-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        allowInput: true
    });

    flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        allowInput: true
    });
</script>
@endsection