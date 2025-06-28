<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <h2 style="text-align: center; margin-bottom: 25px;"><i class="fa-solid fa-cash-register"></i> POS System Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" class="form-control" type="text" name="username" :value="old('username')" required autofocus />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
        </div>

        <!-- Show Password -->
        <div class="form-group-checkbox">
            <input type="checkbox" id="show-password">
            <label for="show-password">Show Password</label>
        </div>

        {{-- THE FIX: Applied direct flexbox styles to correctly position the elements --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
            <div>
                @if (Route::has('password.request'))
                    <a style="text-decoration: underline; font-size: 0.875rem; color: #4B5563;" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div style="display: flex; align-items: center;">
                 <a style="text-decoration: underline; font-size: 0.875rem; color: #4B5563; margin-right: 1rem;" href="{{ route('register') }}">
                    {{ __('Register') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('Log in') }}
                </button>
            </div>
        </div>
    </form>
</x-guest-layout>

{{-- JavaScript for "Show Password" --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('show-password');

        if (passwordInput && showPasswordCheckbox) {
            showPasswordCheckbox.addEventListener('change', function() {
                passwordInput.type = this.checked ? 'text' : 'password';
            });
        }
    });
</script>
