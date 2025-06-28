@extends('layouts.app')

@section('styles')
<style>
    /* Dark Mode specific styles for form elements */
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
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-user-pen"></i> Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>

<form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Employee Details</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="first_name">First Name</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="last_name">Last Name</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="position">Position</label><input type="text" name="position" class="form-control" value="{{ old('position', $employee->position) }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="hire_date">Hire Date</label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d')) }}" required></div></div>
                    </div>
                     <div class="row">
                        <div class="col-md-6"><div class="form-group"><label for="phone_number">Phone</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $employee->phone_number) }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="email">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required></div></div>
                    </div>
                    <div class="form-group">
                        <label for="user_id">Link to User Account</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- No Linked Account --</option>
                            @foreach($users as $user)
                                {{-- Select the current employee's linked user, and disable users who are already linked to someone else --}}
                                <option value="{{ $user->id }}" 
                                    {{ (old('user_id', $employee->user_id) == $user->id) ? 'selected' : '' }} 
                                    {{ $user->employee && $user->employee->id !== $employee->id ? 'disabled' : '' }}>
                                    {{ $user->username }} {{ $user->employee && $user->employee->id !== $employee->id ? '('.$user->employee->first_name.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cv">Upload New CV (Optional)</label>
                        <input type="file" name="cv" class="form-control">
                        <small class="form-text text-muted">Uploading a new CV will replace the old one.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card">
                <div class="card-header">Current CV</div>
                <div class="card-body" style="text-align:center;">
                    @if($employee->cv_path)
                         <a href="{{ asset('storage/' . $employee->cv_path) }}" target="_blank">View Current CV</a>
                    @else
                        <p class="text-muted">No CV on file.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Employee</button>
        <a href="{{ route('employees.index') }}" 
           style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">
           Cancel
        </a>
    </div>
</form>
@endsection
