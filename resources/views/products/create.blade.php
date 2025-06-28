@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-plus"></i> Add New Product</h1>

<div class="card">
    <div class="card-header">
        <h2>New Product Details</h2>
    </div>
    <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            {{-- This is the container for the image preview --}}
            <div class="image-preview-container" style="margin-top: 15px; width: 200px; height: 200px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center;">
                <img id="image-preview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 100%; display: none;"/>
                <span id="preview-text">Image Preview</span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
         <a href="{{ route('products.index') }}" class="btn" style="background-color: #777;">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
{{-- THE FIX: Added the JavaScript for the image preview --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewText = document.getElementById('preview-text');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            previewText.style.display = 'none';
            imagePreview.style.display = 'block';

            reader.onload = function(event) {
                imagePreview.setAttribute('src', event.target.result);
            }
            reader.readAsDataURL(file);
        } else {
            previewText.style.display = 'block';
            imagePreview.style.display = 'none';
            imagePreview.setAttribute('src', '#');
        }
    });
});
</script>
@endpush
