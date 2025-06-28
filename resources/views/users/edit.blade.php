@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-user-pen"></i> Edit User</h1>

<div class="card">
    <div class="card-header">
        <h2>Editing details for "{{ $user->username }}"</h2>
    </div>
    <form method="post" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- This is important for update forms --}}

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
        </div>
        
        <div class="form-group">
            <label for="profile_picture">Profile Picture (leave empty to keep current picture)</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
            <div class="image-preview-container" style="margin-top: 15px; width: 150px; height: 150px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                {{-- THE FIX: Set the image source to the user's current picture and hide the placeholder text. --}}
                <img id="image-preview" src="{{ asset('storage/avatars/' . $user->profile_picture) }}" alt="Image Preview" style="max-width: 100%; max-height: 100%; border-radius: 50%; object-fit: cover; display: block;"/>
                <span id="preview-text" style="display: none;">Avatar Preview</span>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="********" disabled>
            <small>Password cannot be changed from this screen.</small>
        </div>
        
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn" style="background-color: #777;">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
{{-- This script handles the image preview functionality when a NEW file is selected --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const imageInput = document.getElementById('profile_picture');
    const imagePreview = document.getElementById('image-preview');
    const previewText = document.getElementById('preview-text');

    if(imageInput) {
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
            }
        });
    }
});
</script>
@endpush
