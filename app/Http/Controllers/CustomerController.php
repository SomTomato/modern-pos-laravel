<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('id', '!=', 1)->orderBy('name')->get();
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Customer Management']];
        return view('customers.index', compact('customers', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'phone_number' => 'required|string|unique:customers,phone_number']);
        try {
            Customer::create($request->all());
            return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not add customer. Please try again.')->withInput();
        }
    }

    public function destroy(Customer $customer)
    {
        if ($customer->id == 1) {
            return redirect()->route('customers.index')->with('error', 'Cannot delete the default customer.');
        }
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
