<x-pg-auth title="Sign in" eyebrow="Welcome back" heading="Sign in to your workspace" subtitle="Your next great marketing campaign starts right here.">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
            <label for="email">Work email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" required autofocus autocomplete="username">
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
        </div>
        <div class="meta">
            <label class="check" for="remember_me"><input id="remember_me" type="checkbox" name="remember">Remember me</label>
            @if(Route::has('password.request'))<a href="{{ route('password.request') }}">Forgot password?</a>@endif
        </div>
        <button class="submit" type="submit">Sign in to PostGenius →</button>
    </form>
    @if(Route::has('register'))<p class="switch">New to PostGenius? <a href="{{ route('register') }}">Create an account</a></p>@endif
</x-pg-auth>
