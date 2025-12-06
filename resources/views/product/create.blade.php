@extends('layouts.app')

@section('content')
<div class="container animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_product') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.product_name') }}</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('messages.price') }}</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">{{ __('messages.image') }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">{{ __('messages.category') }}</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (old('category_id') ?? ($model->category_id ?? '')) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                   
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('messages.Description') }}</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite">
                                <i class="bi bi-save"></i> {{ __('messages.Add Product') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
