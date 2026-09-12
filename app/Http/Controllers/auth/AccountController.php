<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on User Role requirement
            $user = Auth::user();
            if ($user->role === 'customer') {
                return redirect()->intended(route('loans.index'));
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['nullable', 'string', 'in:admin,loan_officer,cashier,customer'],
        ]);

        // Default new registrants to 'customer' if no role is supplied
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('loans.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
        // View all pending loans (For Loan Officer / Admin)
    public function pending()
    {
        $loans = Loan::where('status', 'Pending')->with('customer')->latest()->get();
        return view('loans.pending', compact('loans'));
    }

    // Approve a loan application
    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== 'Pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }

        $loan->update([
            'status' => 'Approved'
        ]);

        return back()->with('success', 'Loan approved successfully.');
    }
}