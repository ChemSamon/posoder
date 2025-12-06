@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Styling for the large icons on summary cards */
    .dashboard-icon-lg {
        font-size: 2.5rem;
        opacity: 0.7;
    }
    /* Simple card shadow for a modern look */
    .summary-card {
        border: none !important;
        border-radius: 0.5rem;
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-2px);
    }
    /* Sticky header for Order History table */
    .table-sticky-header thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa; /* Light grey background */
        z-index: 10;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    /* Custom utility colors not in standard BS */
    .text-purple { color: #6f42c1; }
    .text-orange { color: #fd7e14; }

</style>

<div class="container-fluid py-4">
    <h2 class="mb-4 text-dark fw-bold border-bottom pb-2">
        <i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('messages.Dashboard Overview') }}
    </h2>

    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card summary-card bg-danger-subtle shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase text-danger fw-bold mb-1" style="font-size: 0.75rem;">{{ __('messages.Product (items)') }}</div>
                            <div class="h3 mb-0 fw-bolder text-dark">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cup-hot-fill text-danger dashboard-icon-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card summary-card bg-primary-subtle shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase text-primary fw-bold mb-1" style="font-size: 0.75rem;">{{ __('messages.Category (items)') }}</div>
                            <div class="h3 mb-0 fw-bolder text-dark">{{ $totalCategories }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tags-fill text-primary dashboard-icon-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card summary-card bg-success-subtle shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase text-success fw-bold mb-1" style="font-size: 0.75rem;">{{ __('messages.Daily Sales') }}</div>
                            <div class="h4 mb-0 fw-bolder text-dark">
                                ${{ number_format($dailySales, 2) }}
                            </div>
                            <div class="small text-success mt-1">
                                <i class="bi bi-arrow-up-right"></i> ៛{{ number_format($dailySales * 4100) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin text-success dashboard-icon-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card summary-card bg-info-subtle shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase text-info fw-bold mb-1" style="font-size: 0.75rem;">{{ __('messages.Monthly Sales') }}</div>
                            <div class="h4 mb-0 fw-bolder text-dark">
                                ${{ number_format($monthlySales, 2) }}
                            </div>
                            <div class="small text-info mt-1">
                                <i class="bi bi-calendar-check"></i> ៛{{ number_format($monthlySales * 4100) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bar-chart-line-fill text-info dashboard-icon-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-xl-7 col-lg-7">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                    <h6 class="m-0 fw-bold"><i class="bi bi-graph-up me-1"></i>{{ __('messages.Monthly Sales Overview') }}</h6>
                </div>
                <div class="card-body">
                    <div style="height: 350px;"> <canvas id="topProductsChart"></canvas>
                    </div>
                    </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-5">
            <div class="card shadow h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-receipt-cutoff me-1"></i>{{ __('messages.Recent Orders') }}</h6>
                    <a href="{{ route('orders.history') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.View All') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped table-hover mb-0 table-sticky-header">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.Receipt') }}</th>
                                    <th class="text-end">{{ __('messages.Total') }} ($)</th>
                                    <th class="text-end">{{ __('messages.Final') }} ($)</th>
                                    <th>{{ __('messages.Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="small fw-semibold">{{ $order->receipt_number }}</td>
                                    <td class="text-end text-muted">${{ number_format($order->total, 2) }}</td>
                                    <td class="text-end fw-bold text-success">${{ number_format($order->total_after_discount, 2) }}</td>
                                    <td class="small">{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox me-2"></i>No recent orders found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 	
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/monthly-sales-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('topProductsChart').getContext('2d');
            
            // Define a palette of colors
            const chartColors = [
                'rgba(13, 110, 253, 0.8)',   // Primary - Blue
                'rgba(25, 135, 84, 0.8)',    // Success - Green
                'rgba(220, 53, 69, 0.8)',    // Danger - Red
                'rgba(255, 193, 7, 0.8)',    // Warning - Yellow
                'rgba(111, 66, 193, 0.8)',   // Purple
                'rgba(23, 162, 184, 0.8)',   // Teal
                'rgba(255, 159, 64, 0.8)',   // Orange
                'rgba(52, 58, 64, 0.8)',     // Dark
                'rgba(108, 117, 125, 0.8)',  // Secondary
                'rgba(203, 70, 70, 0.8)',    // Custom Red
                'rgba(70, 203, 192, 0.8)',   // Custom Aqua
                'rgba(170, 150, 220, 0.8)'   // Custom Lavender
            ];

            // Use only the necessary number of colors
            const backgroundColors = data.labels.map((_, index) => chartColors[index % chartColors.length]);
            
            new Chart(ctx, {
                type: 'doughnut', // Changed to Doughnut for a slightly more modern look
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Monthly Sales',
                        data: data.values,
                        backgroundColor: backgroundColors,
                        borderColor: 'rgba(255, 255, 255, 1)', // Solid white border for separation
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allows the height style to work
                    plugins: {
                        legend: {
                            position: 'right', // Legend on the right for better use of space
                            labels: {
                                padding: 10,
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += '$' + context.parsed.toLocaleString('en-US', { minimumFractionDigits: 2 });
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading monthly sales data:', error));
});
</script>
@endpush