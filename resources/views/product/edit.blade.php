@extends('layouts.app')

@section('content')
<div class="container animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white fw-bold">
                    {{ __('messages.edit_product') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.product_name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('messages.price') }}</label>
                            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category Dropdown -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">{{ __('messages.category') }}</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('messages.Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload (Optional) -->
                        <div class="mb-3">
                            <label for="image" class="form-label">{{ __('messages.product_image') }}</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="Current Image" class="mt-2" style="max-height: 150px;">
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg animate__animated animate__pulse animate__infinite">
                                <i class="bi bi-save"></i> {{ __('messages.Update Product') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
