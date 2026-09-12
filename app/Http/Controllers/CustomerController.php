<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        $query->when($request->filled('customer_code'), function ($q) use ($request) {
            $q->where('customer_code', 'like', '%' . $request->customer_code . '%');
        });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->when($request->filled('first_name'), function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->first_name . '%');
        });

        $query->when($request->filled('last_name'), function ($q) use ($request) {
            $q->where('last_name', 'like', '%' . $request->last_name . '%');
        });

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        if ($orderBy === 'name') {
            $query->orderBy('first_name', $orderDir)->orderBy('last_name', $orderDir);
        } else {
            $allowedOrderBy = ['id', 'customer_code', 'first_name', 'last_name', 'city', 'created_at'];
            $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
            $query->orderBy($orderBy, $orderDir);
        }

        $customers = $query->paginate(10)->withQueryString();

        $totalCustomers = Customer::count();

        $activeCustomers = Customer::where('status', 'Active')->count();

        $customersByCity = Customer::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->get();

        return view('customers.index', compact(
            'customers', 
            'totalCustomers', 
            'activeCustomers', 
            'customersByCity'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 10. Validation Rules
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:20', 'unique:customers,customer_code'],
            'first_name'    => ['required', 'string', 'max:50'],
            'last_name'     => ['required', 'string', 'max:50'],
            'gender'        => ['required', Rule::in(['Male', 'Female'])],
            'date_of_birth' => ['required', 'date'],
            'phone'         => ['required', 'string', 'max:20'],
            'email'         => ['required', 'email', 'max:100', 'unique:customers,email'],
            'address'       => ['nullable', 'string'],
            'city'          => ['required', 'string', 'max:100'],
            'status'        => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:20', Rule::unique('customers', 'customer_code')->ignore($customer->id)],
            'first_name'    => ['required', 'string', 'max:50'],
            'last_name'     => ['required', 'string', 'max:50'],
            'gender'        => ['required', Rule::in(['Male', 'Female'])],
            'date_of_birth' => ['required', 'date'],
            'phone'         => ['required', 'string', 'max:20'],
            'email'         => ['required', 'email', 'max:100', Rule::unique('customers', 'email')->ignore($customer->id)],
            'address'       => ['nullable', 'string'],
            'city'          => ['required', 'string', 'max:100'], 
            'status'        => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}