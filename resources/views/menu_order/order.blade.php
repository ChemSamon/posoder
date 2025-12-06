@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order History</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Ordered At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status === 'Completed' ? 'success' : 'secondary' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
