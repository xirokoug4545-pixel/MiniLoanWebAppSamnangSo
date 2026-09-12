<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="h4 fw-bold text-dark mb-1">Create Account</h3>
        <p class="small text-muted mb-0">Easy to apply for a loan</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-secondary">{{ __('Full Name') }}</label>
            <input id="name" class="form-control auth-form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your full name">
            <x-input-error :messages="$errors->get('name')" class="small text-danger mt-1" />
        </div>

        <div class="row g-2 mb-3">
            <div class="col-12 col-sm-6">
                <label for="phone" class="form-label small fw-semibold text-secondary">{{ __('Phone') }}</label>
                <input id="phone" class="form-control auth-form-control" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="Phone">
                <x-input-error :messages="$errors->get('phone')" class="small text-danger mt-1" />
            </div>
            <div class="col-12 col-sm-6">
                <label for="city" class="form-label small fw-semibold text-secondary">{{ __('City') }}</label>
                <input id="city" class="form-control auth-form-control" type="text" name="city" value="{{ old('city') }}" required autocomplete="address-level2" placeholder="City">
                <x-input-error :messages="$errors->get('city')" class="small text-danger mt-1" />
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-secondary">{{ __('Email Address') }}</label>
            <input id="email" class="form-control auth-form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
            <x-input-error :messages="$errors->get('email')" class="small text-danger mt-1" />
        </div>

        <div class="row g-2 mb-3">
            <div class="col-12 col-sm-6">
                <label for="password" class="form-label small fw-semibold text-secondary">{{ __('Password') }}</label>
                <input id="password" class="form-control auth-form-control" type="password" name="password" required autocomplete="new-password" placeholder="Password">
                <x-input-error :messages="$errors->get('password')" class="small text-danger mt-1" />
            </div>
            <div class="col-12 col-sm-6">
                <label for="password_confirmation" class="form-label small fw-semibold text-secondary">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="form-control auth-form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm">
                <x-input-error :messages="$errors->get('password_confirmation')" class="small text-danger mt-1" />
            </div>
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary fw-semibold">
                {{ __('Register') }}
            </button>
        </div>

        <p class="text-center small text-muted mb-0">
            Already registered?
            <a class="fw-semibold text-primary text-decoration-none" href="{{ route('login') }}">
                Sign in
            </a>
        </p>
    </form>
</x-guest-layout>