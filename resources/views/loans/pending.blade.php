@extends('Layouts.app')

@section('title', 'Loan Applications')

@section('main')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pending Loan Applications</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                @if (session('success'))
                    <div class="mb-4 text-green-600 font-medium">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-red-600 font-medium">{{ session('error') }}</div>
                @endif

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Customer</th>
                            <th class="border p-2">Principal ($)</th>
                            <th class="border p-2">Rate (%)</th>
                            <th class="border p-2">Term (Months)</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td class="border p-2 text-center">{{ $loan->id }}</td>
                                <td class="border p-2">
                                    {{ $loan->customer ? $loan->customer->first_name . ' ' . $loan->customer->last_name : 'N/A' }}
                                </td>
                                <td class="border p-2 text-right">${{ number_format($loan->principal_amount, 2) }}</td>
                                <td class="border p-2 text-center">{{ $loan->interest_rate }}%</td>
                                <td class="border p-2 text-center">{{ $loan->term_months }}</td>
                                <td class="border p-2 text-center">
                                    @if (in_array(auth()->user()->role, ['admin', 'loan_officer'], true))
                                        <form action="{{ route('loans.approve', $loan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" style="background-color: #16a34a; color: white;" class="px-3 py-1 rounded text-sm">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">No action available</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border p-4 text-center text-gray-500">No pending loans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection