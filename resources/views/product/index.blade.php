@extends('layouts.app')

@section('content')
<style>
    .product-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-box-seam me-2"></i>{{ __('messages.products') }}
        </h2>

        <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_product') }}
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
                
                <div class="col-md-4 col-lg-3">
                    <label for="categoryFilter" class="form-label text-muted small mb-0">{{ __('messages.Category') }}</label>
                    <select name="category_id" id="categoryFilter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('messages.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <label for="searchFilter" class="form-label text-muted small mb-0">{{ __('messages.Search for a product') }}</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="searchFilter" class="form-control" placeholder="{{ __('messages.Search for a product') }}" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                @if(request('category_id') || request('search'))
                    <div class="col-md-2 col-lg-1 align-self-end">
                         <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                             <i class="bi bi-x-circle me-1"></i> Clear
                         </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg">
        {{-- <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-muted">{{ $products->total() }} Products Found</h5>
        </div> --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 25%;">{{ __('messages.product_name') }}</th>
                            <th style="width: 10%;">{{ __('messages.price') }}</th>
                            <th style="width: 30%;">{{ __('messages.Description') }}</th>
                            <th style="width: 15%;">{{ __('messages.Category') }}</th>
                            <th style="width: 8%;">{{ __('messages.image') }}</th> 
                            <th style="width: 12%;">{{ __('messages.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @forelse($products as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td class="text-success fw-bold">${{ number_format($product->price, 2) }}</td>
                                <td>{{ Str::limit($product->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark">{{ $product->category ? $product->category->name : '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                                    @else
                                        <i class="bi bi-image-fill text-muted" title="No Image"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning" title="{{ __('messages.Edit') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete the product: {{ $product->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.Delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-2"></i>No products found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($products->hasPages())
        <div class="card-footer bg-light d-flex justify-content-center border-top py-3">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection