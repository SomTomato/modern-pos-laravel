@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-id-badge"></i> Employee Management</h1>

<div class="card">
    <div class="card-header">
        <h2>Add New Employee</h2>
    </div>
    <form method="post" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
            </div>
            <div class="form-group">
                <label for="position">Position / Job Title</label>
                <input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}" required>
            </div>
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ old('hire_date', now()->toDateString()) }}" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="user_id">Link to User Account (Optional)</label>
                <select name="user_id" id="user_id" class="form-control">
                    <option value="">-- No Linked Account --</option>
                    @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->username }} ({{$user->role}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cv_file">Upload CV (PDF, Word, JPG, PNG)</label>
                <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Employee</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Employee List</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Contact</th>
                <th>Login Account</th>
                <th>CV</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->position }}</td>
                <td>
                    {{ $employee->phone_number }}<br>
                    <small>{{ $employee->email }}</small>
                </td>
                <td>
                    @if($employee->user)
                        <span class="status-badge active">{{ $employee->user->username }}</span>
                    @else
                        <span class="status-badge disabled">N/A</span>
                    @endif
                </td>
                <td>
                    @if($employee->cv_path)
                        <a href="{{ asset('storage/' . $employee->cv_path) }}" target="_blank" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9em;">View CV</a>
                    @else
                        <span>None</span>
                    @endif
                </td>
                <td>
                     <a href="#" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No employees have been added yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
