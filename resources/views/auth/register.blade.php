<x-pg-auth title="Create account" eyebrow="Get started" heading="Build your AI marketing team" subtitle="Create your account and start organizing smarter marketing campaigns.">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field">
            <label for="name">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required autofocus autocomplete="name" maxlength="255">
        </div>
        <div class="field">
            <label for="email">Work email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" required autocomplete="username" maxlength="255">
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Create a secure password" required autocomplete="new-password">
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter your password" required autocomplete="new-password">
        </div>
        <button class="submit" type="submit">Create my account →</button>
    </form>
    <p class="switch">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    <p style="font-size:12px;color:#8994aa;line-height:1.6;text-align:center;margin-top:20px">By creating an account, you agree to our <a href="{{ url('/terms') }}" style="color:#6255dd">Terms</a> and <a href="{{ url('/privacy') }}" style="color:#6255dd">Privacy Policy</a>.</p>
</x-pg-auth>
