<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with('user')->latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        $users = User::with('employee')->orderBy('username')->get();
        return view('employees.create', compact('users'));
    }

    /**
     * THE FIX: Updated validation to use 'phone_number'.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'phone_number' => 'nullable|string|max:20', // Changed from 'phone'
            'email' => 'required|email|unique:employees,email',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id',
            'cv' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('cv');

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('resumes', 'public');
            $data['cv_path'] = $path;
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $users = User::with('employee')->orderBy('username')->get();
        return view('employees.edit', compact('employee', 'users'));
    }

    /**
     * THE FIX: Updated validation to use 'phone_number'.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'phone_number' => 'nullable|string|max:20', // Changed from 'phone'
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id,' . $employee->id,
            'cv' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('cv');

        if ($request->hasFile('cv')) {
            if ($employee->cv_path) {
                Storage::disk('public')->delete($employee->cv_path);
            }
            $path = $request->file('cv')->store('resumes', 'public');
            $data['cv_path'] = $path;
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee details updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->cv_path) {
            Storage::disk('public')->delete($employee->cv_path);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee has been deleted.');
    }
}
