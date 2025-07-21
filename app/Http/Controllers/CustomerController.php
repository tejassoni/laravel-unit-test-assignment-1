<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::orderBy('updated_at', 'desc')->get();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255', 
            'lastname' => 'required|string|max:255', 
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required|string|max:13',
            'address' => 'nullable|string',
            'gender' => 'required|in:male,female', 
            'hobbies' => 'nullable|array', // Hobbies should be an array
            'hobbies.*' => 'string|max:50', // Each hobby should be a string
        ]);

        // Handle hobbies: if not provided, ensure it's an empty array for consistent storage
        if (!isset($validatedData['hobbies'])) {
            $validatedData['hobbies'] = [];
        }

        Customer::create($validatedData);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
         $validatedData = $request->validate([
            'firstname' => 'required|string|max:255', 
            'lastname' => 'required|string|max:255', 
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'mobile' => 'required|string|max:13',
            'address' => 'nullable|string',
            'gender' => 'required|in:male,female', 
            'hobbies' => 'nullable|array', // Hobbies should be an array
            'hobbies.*' => 'string|max:50', // Each hobby should be a string
        ]);

        if (!isset($validatedData['hobbies'])) {
            $validatedData['hobbies'] = [];
        }

        $customer->update($validatedData); // Update method also uses casting

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
                ->withSuccess('Customer Deleted Successfully.');
    }
}
