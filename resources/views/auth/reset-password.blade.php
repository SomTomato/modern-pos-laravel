<x-guest-layout>
    {{-- Removed the default x-auth-card wrapper --}}

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <x-label for="email" :value="__('Email')" />
            <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-label for="password" :value="__('Password')" />
            <x-input id="password" class="form-control" type="password" name="password" required />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button>
                {{ __('Reset Password') }}
            </x-button>
        </div>
    </form>
</x-guest-layout>
