@extends('layouts.app')

@section('styles')
<style>
    /* Dark Mode specific styles for form elements and the preview box */
    .dark-mode .form-control {
        background-color: #2d3748;
        color: #e2e8f0;
        border-color: #4a5568;
    }
    .dark-mode .form-control:focus {
        background-color: #2d3748;
        color: #e2e8f0;
        border-color: #805ad5;
        box-shadow: none;
    }
    .dark-mode .form-group label {
        color: #a0aec0;
    }
    .dark-mode #preview-container {
        background-color: #2d3748 !important;
        border-color: #4a5568 !important;
    }
    .dark-mode #preview-container .text-muted {
        color: #a0aec0 !important;
    }
     .dark-mode .fa-file-alt {
        color: #a0aec0 !important;
    }
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-user-plus"></i> Add New Employee</h1>

<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h2>Employee Details</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="first_name">First Name</label><input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>@error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                        <div class="col-md-6"><div class="form-group"><label for="last_name">Last Name</label><input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>@error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="position">Position / Job Title</label><input type="text" name="position" class="form-control" value="{{ old('position') }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="hire_date">Hire Date</label><input type="date" name="hire_date" class="form-control @error('hire_date') is-invalid @enderror" value="{{ old('hire_date', now()->format('Y-m-d')) }}" required>@error('hire_date') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="phone_number">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="email">Email Address</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    </div>

                    <div class="form-group">
                        <label for="user_id">Link to User Account (Optional)</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- No Linked Account --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }} {{ $user->employee ? 'disabled' : '' }}>
                                    {{ $user->username }} ({{ $user->email }}) {{ $user->employee ? '- Linked to ' . $user->employee->first_name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cv">Upload CV (PDF, Word, JPG, PNG)</label>
                        <input type="file" name="cv" id="cv-upload" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        @error('cv') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h2>CV Preview</h2>
                </div>
                <div class="card-body">
                    <div id="preview-container" style="min-height: 300px; border: 2px dashed #ccc; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background-color: #f8f9fa;">
                         <span class="text-muted">Preview will appear here</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Add Employee</button>
        {{-- THE FIX: Added robust inline styles to force the Cancel link to appear as a button --}}
        <a href="{{ route('employees.index') }}" 
           style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">
           Cancel
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('cv-upload').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
    const file = event.target.files[0];

    if (file) {
        const fileType = file.type;
        const fileName = file.name;
        const reader = new FileReader();

        if (fileType.startsWith('image/')) {
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '400px';
                img.style.objectFit = 'contain';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
        else if (fileType === 'application/pdf') {
            const embed = document.createElement('embed');
            embed.src = URL.createObjectURL(file);
            embed.type = 'application/pdf';
            embed.style.width = '100%';
            embed.style.height = '400px';
            previewContainer.appendChild(embed);
        }
        else {
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-file-alt fa-5x text-muted';
            const text = document.createElement('p');
            text.className = 'mt-3 text-muted';
            text.innerText = fileName;
            previewContainer.appendChild(icon);
            previewContainer.appendChild(text);
        }
    } else {
        previewContainer.innerHTML = '<span class="text-muted">Preview will appear here</span>';
    }
});
</script>
@endpush
