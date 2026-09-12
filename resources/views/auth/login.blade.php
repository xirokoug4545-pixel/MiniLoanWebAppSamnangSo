<x-guest-layout>
    <div class="mb-4">
        <h1 class="h4 fw-bold text-dark mb-1">{{ __('Welcome Back') }}</h1>
        <p class="small text-muted mb-0">{{ __('Sign in to manage your MiniLoan account securely.') }}</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-secondary">{{ __('Email Address') }}</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                <input id="email" class="form-control auth-form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email address">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label small fw-semibold text-secondary mb-1">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="small text-primary text-decoration-none" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                <input id="password" class="form-control auth-form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check mb-0">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label small text-secondary">{{ __('Remember me') }}</label>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-3 auth-submit">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center small text-muted mb-0">
                {{ __('Don\'t have an account?') }}
                <a class="fw-semibold text-primary text-decoration-none" href="{{ route('register') }}">
                    {{ __('Sign up') }}
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>