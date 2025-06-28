@extends('layouts.app')

@section('content')
{{-- THE FIX: Removed the button from the top to place it back in the card --}}
<h1><i class="fa-solid fa-user"></i> Employee Profile</h1>

<div class="row">
    {{-- Left Column for Employee Details --}}
    <div class="col-md-8">
        <div class="card">
            {{-- THE FIX: Moved the 'Edit' button back inside the card header --}}
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                 <h2>{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                 <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary"><i class="fa-solid fa-user-pen"></i> Edit Employee</a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 30%;">Position:</th>
                        <td>{{ $employee->position ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Hire Date:</th>
                        <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Email Address:</th>
                        <td>{{ $employee->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number:</th>
                        <td>{{ $employee->phone_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Linked User Account:</th>
                        <td>{{ $employee->user->username ?? 'None' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column for CV Document --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>CV Document</h2>
            </div>
            <div class="card-body" style="text-align: center;">
                @if($employee->cv_path)
                    @if(in_array(strtolower(pathinfo($employee->cv_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $employee->cv_path) }}" alt="CV Preview" style="width:100%; max-width: 250px; border-radius: 5px; margin-bottom: 15px;">
                    @else
                        <i class="fa-solid fa-file-alt fa-5x text-muted my-3"></i>
                        <p>{{ basename($employee->cv_path) }}</p>
                    @endif
                     <a href="{{ asset('storage/' . $employee->cv_path) }}" target="_blank" class="btn btn-success btn-block">Download / View Full CV</a>
                @else
                    <p class="text-muted">No CV has been uploaded for this employee.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    {{-- THE FIX: Added robust inline styles to force the 'Back' link to appear as a button. --}}
    <a href="{{ route('employees.index') }}" 
       style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">
       Back to Employee List
    </a>
</div>
@endsection
