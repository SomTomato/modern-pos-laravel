<x-guest-layout>
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <h2 style="text-align: center; margin-bottom: 25px;"><i class="fa-solid fa-user-plus"></i> Register New Account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" class="form-control" type="text" name="username" :value="old('username')" required autofocus />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
        </div>

        <!-- Show Password -->
        <div class="form-group-checkbox">
            <input type="checkbox" id="show-password-register">
            <label for="show-password-register">Show Password</label>
        </div>

        <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 1rem;">
            <a style="text-decoration: underline; font-size: 0.875rem; color: #4B5563;" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            {{-- THE FIX: Replaced x-button with a styled button --}}
            <button type="submit" class="btn btn-primary" style="margin-left: 1rem;">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>

{{-- THE FIX: Added JavaScript for "Show Password" on both fields --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const showPasswordCheckbox = document.getElementById('show-password-register');

        if (passwordInput && passwordConfirmInput && showPasswordCheckbox) {
            showPasswordCheckbox.addEventListener('change', function() {
                const type = this.checked ? 'text' : 'password';
                passwordInput.type = type;
                passwordConfirmInput.type = type;
            });
        }
    });
</script>
