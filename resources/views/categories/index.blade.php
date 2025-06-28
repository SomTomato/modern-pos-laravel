@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-tags"></i> Category Management</h1>

{{-- Removed old alert messages. The layout partial now handles them. --}}

<div class="card">
    <div class="card-header">
        <h2>Add New Category</h2>
    </div>
    <form method="post" action="{{ route('categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Category Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <div class="image-preview-container" style="margin-top: 15px; width: 200px; height: 200px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center;">
                <img id="image-preview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 100%; display: none;"/>
                <span id="preview-text">Image Preview</span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Existing Categories</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Category Name</th>
                <th style="width: 150px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
            <tr>
                <td>
                    <img class="table-image" src="{{ asset('storage/categories/' . $cat->image) }}" alt="{{ $cat->name }}">
                </td>
                <td>{{ $cat->name }}</td>
                <td style="text-align: right;">
                    <form method="post" action="{{ route('categories.destroy', $cat) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No categories have been created yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

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
@endsection
