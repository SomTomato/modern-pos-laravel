<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    /**
     * Display a listing of all suppliers.
     */
    public function index(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully!');
    }

    /**
     * THE FIX: Display the specified supplier's details.
     */
    public function show(Supplier $supplier): View
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in the database.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from the database.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
