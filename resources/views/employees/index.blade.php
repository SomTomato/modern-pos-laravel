@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-users-gear"></i> Employee Management</h1>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>All Employees</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add New Employee</a>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Name</th>
                    <th style="width: 15%;">Position</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 15%;">Phone Number</th>
                    <th style="width: 10%;">Hire Date</th>
                    <th style="width: 10%;">Linked User</th>
                    <th style="text-align: center; width: 15%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->position ?? 'N/A' }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone_number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') }}</td>
                        <td>{{ $employee->user->username ?? '--' }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 5px;">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary btn-sm">View</a>
                                {{-- THE FIX: Applying a robust inline style to force the correct appearance. --}}
                                <a href="{{ route('employees.edit', $employee) }}" 
                                   class="btn btn-sm" 
                                   style="color: #212529; background-color: #ffc107; border-color: #ffc107; text-decoration: none;">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No employees have been added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
