<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-only');

        $employees = Employee::with('user')->orderBy('last_name')->get();
        // Get users that are NOT yet linked to an employee
        $availableUsers = User::whereDoesntHave('employee')->get();
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Employee Management']];

        return view('employees.index', compact('employees', 'availableUsers', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('admin-only');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'hire_date' => 'required|date',
            'user_id' => 'nullable|unique:employees,user_id|exists:users,id',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB Max
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            // Store the CV in 'storage/app/public/employee_cvs'
            $cvPath = $request->file('cv_file')->store('public/employee_cvs');
            // We only want the path after 'public/', so we strip that part
            $cvPath = str_replace('public/', '', $cvPath);
        }
        
        Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'hire_date' => $request->hire_date,
            'user_id' => $request->user_id,
            'cv_path' => $cvPath,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }
}
