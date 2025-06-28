@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-edit"></i> Edit Product</h1>

<div class="card">
    <div class="card-header">
        <h2>Editing "{{ $product->name }}"</h2>
    </div>
    <form method="post" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image (leave empty to keep current image)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <div style="margin-top: 15px;">
                <p>Current Image:</p>
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="Current Image" class="table-image" />
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn" style="background-color: #777;">Cancel</a>
    </form>
</div>
@endsection