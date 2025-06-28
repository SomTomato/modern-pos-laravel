@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-users-cog"></i> User Management</h1>

<div class="card">
    <div class="card-header">
        <h2>Create New User</h2>
    </div>
    <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
        </div>
        
        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
            <div class="image-preview-container" style="margin-top: 15px; width: 150px; height: 150px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <img id="image-preview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 100%; display: none; border-radius: 50%; object-fit: cover;"/>
                <span id="preview-text">Avatar Preview</span>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Existing Users</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th style="width: 80px;">Picture</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>
                        <img class="table-image" style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover;" src="{{ asset('storage/avatars/' . $user->profile_picture) }}" alt="{{ $user->username }}">
                    </td>
                    <td>{{ $user->username }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>

                        @if ($user->id != 1)
                            <button class="btn btn-danger" disabled>Delete</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
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
            } else {
                previewText.style.display = 'block';
                imagePreview.style.display = 'none';
                imagePreview.setAttribute('src', '#');
            }
        });
    }
});
</script>
@endpush
